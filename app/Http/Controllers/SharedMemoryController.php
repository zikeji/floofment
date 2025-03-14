<?php

namespace App\Http\Controllers;

use App\Collections\SharedMemoryAttachments;
use App\Http\Requests\CreateMemoryRequest;
use App\Models\SharedMemory;
use App\Objects\SharedMemoryAttachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class SharedMemoryController extends Controller
{
    public function create(CreateMemoryRequest $request): RedirectResponse
    {
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
                'memory-attachments',
                $name,
                's3',
            );
        }

        if ($hasVoiceMessage) {
            $request->file('voiceMessage')->storeAs(
                'voice-messages',
                "{$memory->id}.{$voiceMessageExtension}",
                's3'
            );
        }

        return to_route('memory.shared.confirmation');
    }
}
