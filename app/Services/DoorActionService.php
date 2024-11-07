<?php

namespace App\Services;

use App\Models\Add_lock;
use App\Models\Lock;
use App\Models\Card;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class DoorActionService
{
    protected $lock;
    protected string $action;

    public function __construct($action)
    {
        $this->action = $action;
    }

    public function doorAction($cardId, $lockId): JsonResponse
    {
        $this->lock = Lock::find($lockId);
        if (Add_lock::first()->status && !$this->lock) {
                Lock::create(['id' => $lockId]);
                return response()->json(['code' => 3]);
        }

        if (!$lockId || !$this->isValidAction()) {
            return response()->json(['code' => 0]);
        }

        $card = Card::where('uid', $cardId)->first();

        if (!$card) {
            return response()->json(['code' => 2]);
        }

        $door = $this->lock->door;

        switch ($this->action) {
            case 'lock':
                return $this->lockDoor($door);
            case 'unlock':
                return $this->unlockDoor($card, $door);
        }
        return response()->json(['code' => 0, 'error => Action not find']);
    }

    private function isValidAction(): bool
    {
        return in_array($this->action, ['unlock', 'lock']);
    }

    private function lockDoor($door): JsonResponse
    {
        if (empty($door->owner)) {
            return response()->json(['code' => '0']);
        }

        if (Card::find($door->owner)) {
            $door->update(['owner' => null]);
            return response()->json(['code' => 1]);
        }
        return response()->json(['code' => 2]);
    }

    private function unlockDoor($card, $door): JsonResponse
    {
        $second = Carbon::createFromFormat('H:i:s', $door->warn_duration)->secondsSinceMidnight();
        if ($door->owner) {
            if ($this->lock->time_end > now()->timestamp) {
                return response()->json(['code' => '0', 'error' => 'Action repeat']);
            }
        }
        if ($card->level >= $door->level) {
            $door->update(['owner' => $card->id]);
            $this->lock->time_end = now()->timestamp + $second;
            $this->lock->save();
            return response()->json(
                [
                    'code' => 1,
                    'unlockDuration' => $second,
                    'alarmDuration' => Carbon::createFromFormat('H:i:s', $door->warn_duration)->secondsSinceMidnight()
                ]
            );
        } else {
            return response()->json(['code' => 2]);
        }
    }
}
