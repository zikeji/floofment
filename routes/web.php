<?php

use App\Http\Controllers\SharedMemoryController;
use App\Http\Controllers\PhoneRecordingsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [SharedMemoryController::class, 'index'])->name('home');
Route::post('/memory', [SharedMemoryController::class, 'create'])->name('memory.create');

Route::get('/thanks-for-sharing', function () {
    return Inertia::render('MemoryShared');
})->name('memory.shared.confirmation');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('phone-recordings', [PhoneRecordingsController::class, 'index'])->name('phone-recordings');
    Route::patch('phone-recordings/{phoneRecording}', [PhoneRecordingsController::class, 'update'])->name('phone-recordings.update');
    Route::get('phone-recordings/{phoneRecording}/download', [PhoneRecordingsController::class, 'download'])->name('phone-recordings.download');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/webhooks.php';
