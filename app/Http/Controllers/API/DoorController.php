<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Add_lock;
use App\Models\Card;
use App\Models\Door;
use App\Models\Lock;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DoorController extends Controller
{

    public function door(Request $request)
    {
        $action = $request->query('action');
        $lockId = $request->query('lock_id');
        $cardId = $request->query('card_id');
        if(Add_lock::first()->status and !Lock::find($lockId))
        {
            Lock::create(['id'=> $lockId]);
            return response()->json(['code' => 3]);
        }

        if (!$lockId || !$cardId) {
            return response()->json([
                'code' => 0,
                'error' => 'Missing lock_id or card_id'
            ], 400);
        }

        if (!in_array($action, ['unlock', 'lock'])) {
            return response()->json([
                'code' => 0,
                'error' => 'Invalid action'
            ], 400);
        }
        try {
            $card = Card::where('uid', $cardId)->firstOrFail();
            $lock = Lock::findOrFail($lockId);
            $door = $lock->door;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'code' => 0,
                'error' => $e->getMessage()
            ]);
        }
        $responce = $card->level >= $door->level;

        if (!empty($responce)) {
            switch ($action) {
                case 'lock':
                    $door->update(['owner' => null]);
                    break;
                case 'unlock':
                    $door->update(['owner' => $card->id]);
                    break;
                default:
                    $error = 'Dont know action';
                    break;
            }
        }
        return response()->json([
            'code' => $responce? 1:2,
            'error' => $error ?? null
        ]);
    }

    public function add_lock()
    {
        $add_lock = Add_lock::first();
        $add_lock->status = !$add_lock->status;
        $add_lock->save();
        return response()->json(['status' => $add_lock->status]);
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
