<?php

namespace App\Http\Controllers;

use App\Collections\SharedMemoryAttachments;
use App\Http\Requests\CreateMemoryRequest;
use App\Models\SharedMemory;
use App\Objects\SharedMemoryAttachment;
use App\Rules\TurnstileValid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ShareMemoryController extends Controller
{
    public function index()
    {
        return Inertia::render('share-memory/Create');
    }

    public function success()
    {
        return Inertia::render('share-memory/Success');
    }

    public function create(CreateMemoryRequest $request): RedirectResponse
    {
        $validator = Validator::make(['token' => $request->input('turnstileToken')], ['token' => ['sometimes', 'nullable', new TurnstileValid]]);
        if (! $validator->passes()) {
            return back()->withErrors($validator->errors());
        }

        $executed = RateLimiter::attempt(
            "submit-memory-{$request->session()->getId()}",
            3,
            fn () => true,
            120,
        );

        if (! $executed) {
            return back()->withErrors([
                'rateLimit' => 'You are submitting too many memories. Please try again later.',
            ]);
        }

        $hasVoiceMessage = $request->hasFile('voiceMessage');
        $voiceMessageExtension = $hasVoiceMessage ? $request->file('voiceMessage')->extension() : null;

        /** @var \Illuminate\Support\Collection<string, \Illuminate\Http\UploadedFile> */
        $files = collect();
        $attachments = SharedMemoryAttachments::make();
        foreach ($request->file('files', []) as $file) {
            $attachments->add(
                new SharedMemoryAttachment(
                    Str::ulid()->toString(),
                    $file->getFilename(),
                    $file->getMimeType(),
                    $file->extension(),
                    $file->getSize()
                )
            );
            $files->offsetSet("{$attachments->last()->id}.{$file->extension()}", $file);
        }

        $memory = SharedMemory::create([
            ...$request->validated(),
            'has_voice_message' => $hasVoiceMessage,
            'voice_message_extension' => $voiceMessageExtension,
            'attachments' => $attachments,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        foreach ($files as $name => $file) {
            $file->storeAs(
                'memory_attachments',
                $name,
                's3',
            );
        }

        if ($hasVoiceMessage) {
            $request->file('voiceMessage')->storeAs(
                'voice_messages',
                "{$memory->id}.{$voiceMessageExtension}",
                's3'
            );
        }

        return to_route('memory.shared.confirmation');
    }
}
