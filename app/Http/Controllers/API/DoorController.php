<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Door;
use App\Models\Lock;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DoorController extends Controller
{
    public function door($id, $room_id)
    {
        $card = Card::where('id', $id)->get();
        $door = Door::where('id', $room_id)->get();

        if ($id) {
            return response()->json([
                'status' => $card,
                'door' => $door
            ], 200);
        } else {
            return response()->json([
                'status' => false
            ], 200);
        }

    }

    public function test($action, $lock_id, $sha)
    {
        try {
            $card = Card::find($lock_id); //Card::class->firstWhere('sha', $sha);
            $lock = Lock::class->find($lock_id);
            $door = $lock->door;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }

        $responce = $card->level > $door->level;

        if (!empty($responce)) {
            switch ($action) {
                case 'lock':
                    $door->update(['owner' => null]);
                    break;
                case 'unlock':
                    $door->update(['owner' => $card_id]);
                    break;
                default:
                    $error = 'Dont know action';
                    break;
            }
        }
        return response()->json([
            'response' => $responce,
            'error' => $error ?? null
        ]);
    }

    public function test_no_validate($action, $lock_id, $card_id)
    {

    }

    public function linkLockWithDoor($lock_id, $door_id)
    {
        try {
            Lock::find($lock_id)->update(['door_id' => $door_id]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['result' => $e->getMessage()]);
        }
        return response()->json(['result' => 'SUCCESS']);
    }

    public function getDoors()
    {
        $doors = Door::get();
        return response()->json([$doors]);
    }

    public function getCards()
    {
        $cards = Card::get();
        return response()->json([$cards]);
    }

    public function getLocks()
    {
        $locks = Lock::get();
        return response()->json([$locks]);
    }
}
