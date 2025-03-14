<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Sidebar', function () {
    return auth()->hasUser();
});

Broadcast::channel('App.Models.PhoneRecording', function () {
    return auth()->hasUser();
});

Broadcast::channel('App.Models.SharedMemory', function () {
    return auth()->hasUser();
});
