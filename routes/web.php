<?php

use App\Http\Controllers\SharedMemoriesController;
use App\Http\Controllers\ShareMemoryController;
use App\Http\Controllers\PhoneRecordingsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [ShareMemoryController::class, 'index'])->name('home');
Route::post('/memory', [ShareMemoryController::class, 'create'])->name('memory.create');
Route::get('/thanks-for-sharing', [ShareMemoryController::class, 'success'])->name('memory.shared.confirmation');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('phone-recordings', [PhoneRecordingsController::class, 'index'])->name('phone-recordings');
    Route::patch('phone-recordings/{phoneRecording}', [PhoneRecordingsController::class, 'update'])->name('phone-recordings.update');
    Route::get('phone-recordings/{phoneRecording}/download', [PhoneRecordingsController::class, 'download'])->name('phone-recordings.download');

    Route::get('shared-memories', [SharedMemoriesController::class, 'index'])->name('shared-memories');
    Route::get('shared-memories/{sharedMemory}/download-voice-message', [SharedMemoriesController::class, 'downloadVoiceMessage'])->name('shared-memories.download-voice-message');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/webhooks.php';
