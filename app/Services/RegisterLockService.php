<?php

namespace App\Services;

use App\Models\Lock;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterLockService
{
    public  function  registerLock($lockId, $auth):string
    {
        if(Cache::get('authCode') == $auth){
            $token = Str::random(256);
            $hash = Hash::make($token);
            Lock::create(['uid'=>$lockId,'token'=>$hash]);
            return $token;
        }
        return '';
    }
}
