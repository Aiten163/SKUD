<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Door;
use Illuminate\Http\Request;

class DoorController extends Controller
{
    public function door($id, $room_id)
    {
        $Card = Card::where('id', $id)->get();
        $Door = Door::where('id', $room_id)->get();

        if ($id) {
            return response()->json([
                'status' => $Card,
                'door' => $Door
            ], 200);
        } else {
            return response()->json([
                'status' => false
            ], 200);
        }

    }
}
