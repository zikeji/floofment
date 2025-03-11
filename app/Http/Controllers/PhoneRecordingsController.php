<?php

namespace App\Http\Controllers;

use App\Models\PhoneRecording;
use App\PhoneRecordingStatus;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PhoneRecordingsController extends Controller
{
    public function index(): Response {
        return Inertia::render('PhoneRecordings', [
            'recordings' => PhoneRecording::select()->orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function download(PhoneRecording $phoneRecording) {
        if ($phoneRecording->status !== PhoneRecordingStatus::Available) {
            throw new NotFoundHttpException;
        }
        return Storage::disk('s3')->download("phone_recordings/{$phoneRecording->sid}.mp3");
    }
}
