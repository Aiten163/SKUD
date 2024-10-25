<?php

use App\Http\Controllers\API\DoorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(DoorController::class)->group(function () {
    Route::get('/door/{id}/{room_id}', 'door')->name('door');

    Route::get('/door/test/{action}/{lock_id}/{card_id}', 'test')->name('test');
    Route::get('/door/test_no_validate/{action}/{lock_id}/{card_id}', 'test_no_validate')->name('test_no_validate');


    Route::get('/door/link/{lock_id}/{card_id}', 'linkLockWithDoor')->name('LinkLockWithDoor');

    Route::get('/door/getDoors', 'getDoors')->name('getDoors');
    Route::get('/door/getLocks', 'getLocks')->name('getLocks');
    Route::get('/door/getCards', 'getCards')->name('getCards');
});

