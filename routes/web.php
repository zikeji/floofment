<?php

use App\Http\Controllers\PhoneRecordingsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('phone-recordings', [PhoneRecordingsController::class, 'index'])->name('phone-recordings');
    Route::get('phone-recordings/{phoneRecording}/download', [PhoneRecordingsController::class, 'download'])->name('phone-recordings.download');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/webhooks.php';
