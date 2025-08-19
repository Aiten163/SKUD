<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\WebsocketController;

Route::get('/', function () {
    return redirect('/admin/logs');
});
Route::get('/ws', [WebsocketController::class, 'connect'])->name('websocket');
