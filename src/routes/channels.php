<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('auth', function () {
    return true;
});
Broadcast::channel('websocketTest', function () {
    return true;
});
