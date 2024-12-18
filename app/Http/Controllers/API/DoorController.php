<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Add_lock;
use App\Models\Card;
use App\Models\Door;
use App\Models\DoorLog;
use App\Models\Lock;
use App\Services\DoorActionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoorController extends Controller
{
    public function door(Request $request)
    {
        $action = $request->query('action');
        $lockId = $request->query('lock_id');
        $cardId = $request->query('card_id');
        $doorAction = new DoorActionService($action);
        $res = $doorAction->doorAction($cardId, $lockId);
        try {
            $doorAction->createLog($res['code'], isset($res['unlockDuration']));
        } catch (ModelNotFoundException $e) {
            DoorLog::create(['action'=>'Ошибка: ' . $e->getMessage()]);
        }
        return response()->json($res);
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
