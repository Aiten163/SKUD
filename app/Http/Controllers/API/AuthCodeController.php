<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthCodeController extends Controller
{
    public function generateCode(Request $request)
    {
        $code = random_int(100000, 999999);

        Cache::put('authCode', $code, $request->input('time', 60));

        return response()->json(['code' => $code]);
    }
    public  function deleteCode()
    {
        Cache::forget('authCode');

        return response()->json(['message' => 'Code removed successfully.']);
    }
}
