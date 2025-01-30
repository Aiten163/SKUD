<?php

use App\Http\Controllers\API\AuthCodeController;
use App\Http\Controllers\API\DoorController;
use App\Http\Controllers\API\RegisterLockController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(DoorController::class)->group(function () {

    Route::get('/door', 'door')->name('door');

    Route::get('/add_lock', 'add_lock')->name('add_lock');

    Route::get('/door/test_no_validate/{action}/{lock_id}/{card_id}', 'test_no_validate')->name('test_no_validate');


    Route::get('/door/link/{lock_id}/{card_id}', 'linkLockWithDoor')->name('LinkLockWithDoor');

    Route::get('/door/getDoors', 'getDoors')->name('getDoors');
    Route::get('/door/getLocks', 'getLocks')->name('getLocks');
    Route::get('/door/getCards', 'getCards')->name('getCards');
});

Route::post('/generate-code', [AuthCodeController::class, 'generateCode']);
Route::post('/remove-code', [AuthCodeController::class, 'deleteCode']);

Route::get('/register/{lockId}/{auth}', [RegisterLockController::class, 'registerLock']);
Route::get('/w', function ($e=49681321) {
    event('messageToLock', $e);
    return response()->json($e);
} );
