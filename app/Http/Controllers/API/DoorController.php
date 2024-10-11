<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoorController extends Controller
{
    public function door($id, $room_id)
    {
        if ($id == 1) {
            return response()->json([
                'status' => true
            ], 200);
        } else {
            return response()->json([
                'status' => false
            ], 200);
        }

    }
}
