<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoorController extends Controller
{
    public function door($id)
    {

        return response()->json([
            'status'=> $status
        ], 200);
    }
}
