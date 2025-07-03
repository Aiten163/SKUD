<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\RegisterLockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegisterLockController extends Controller
{
    protected RegisterLockService $registerLockService;

    public function __construct(RegisterLockService $registerLockService)
    {
        $this->registerLockService = $registerLockService;
    }
    public function registerLock($lockId, $auth):object
    {
        $token = $this->registerLockService->registerLock($lockId, $auth);
        if ($token) {
            return response()->json(['token' => $token]);
        }
        return response()->json(['code' => 0]);
    }
}
