<?php

use App\Http\Controllers\API\DoorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/door/{id}/{room_id}', [doorcontroller::class, 'door'])->name('door');
