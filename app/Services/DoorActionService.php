<?php

namespace App\Services;

use App\Models\Add_lock;
use App\Models\DoorLog;
use App\Models\Lock;
use App\Models\Card;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DoorActionService
{
    protected  $lock;
    protected  $card;
    protected  $action;

    public function __construct($action)
    {
        $this->action = $action;
    }

    public function doorAction($cardUid, $lockId): array
    {
        try {
            $this->lock = Lock::findOrFail($lockId);
        } catch (ModelNotFoundException $e) {
            $this->lock = new Lock();
            $this->lock->id = 0;
        }

        if (Add_lock::first()->status && $this->lock->id==0) {
                Lock::create(['id' => $lockId]);
                return ['code' => 3];
        }

        if (!$lockId || !$this->isValidAction()) {
            return ['code' => 0];
        }

        $this->card = Card::where('uid', $cardUid)->first();
        if (!$this->card) {
            return ['code' => 2];
        }
        $door = $this->lock->door;
        switch ($this->action) {
            case 'lock':
                return $this->lockDoor($door);
            case 'unlock':
                return $this->unlockDoor($door);
        }
        return ['code' => 0, 'error => Action not find'];
    }

    private function isValidAction(): bool
    {
        return in_array($this->action, ['unlock', 'lock']);
    }

    private function lockDoor($door): array
    {
        if (empty($door->owner)) {
            return ['code' => '0'];
        }

        if (Card::find($door->owner)) {
            $door->update(['owner' => null]);
            return ['code' => 1];
        }
        return ['code' => 2];
    }

    private function unlockDoor($door): array
    {
        $second = Carbon::createFromFormat('H:i:s', $door->unlock_duration)->secondsSinceMidnight();
        if ($door->owner) {
            if ($this->lock->time_end > now()->timestamp) {
                return ['code' => '0', 'error' => 'Action repeat'];
            }
        }
        if ($this->card->level >= $door->level) {
            $door->update(['owner' => $this->card->id]);
            $this->lock->time_end = now()->timestamp + $second;
            $this->lock->save();
            return
                [
                    'code' => 1,
                    'unlockDuration' => $second,
                    'alarmDuration' => Carbon::createFromFormat('H:i:s', $door->warn_duration)->secondsSinceMidnight()
                ]
            ;
        } else {
            return ['code' => 2];
        }
    }
    public function createLog($code, $open)
    {
        switch ($code) {
            case '0':
                $action = 'Ошибка';
                break;
            case '1':
                $action = $open ? 'Дверь открыта' : 'Дверь закрыта';
                break;
            case '2':
                $action = 'Нет доступа';
                break;
            case '3':
                $action = 'Дверь привязана';
                break;
        }
        DoorLog::create([
            'action' => isset($action)?$action:null,
            'card_id' => isset($this->card->id)?$this->card->id:null,
            'door_id' => isset($this->lock->door_id)?$this->lock->door_id:null,
        ]);
    }
}

