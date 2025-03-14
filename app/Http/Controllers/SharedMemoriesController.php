<?php

namespace App\Http\Controllers;

use App\Models\SharedMemory;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SharedMemoriesController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('SharedMemories', [
            'sharedMemories' => SharedMemory::select()->orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function downloadVoiceMessage(SharedMemory $sharedMemory)
    {
        $path = "voice_messages/{$sharedMemory->id}.{$sharedMemory->voice_message_extension}";

        if (! $sharedMemory->has_voice_message || ! Storage::disk('s3')->exists($path)) {
            throw new NotFoundHttpException;
        }

        return Storage::disk('s3')->download(
            $path,
            "{$sharedMemory->created_at->format('Y-m-d')}_{$sharedMemory->id}.{$sharedMemory->voice_message_extension}",
        );
    }
}
