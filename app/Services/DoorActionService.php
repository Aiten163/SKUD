<?php

namespace App\Services;

use App\Models\Add_lock;
use App\Models\DoorLog;
use App\Models\Lock;
use App\Models\Card;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class DoorActionService
{
    protected Lock $lock;
    protected int $cardId;
    protected string $action;

    public function __construct($action)
    {
        $this->action = $action;
    }

    public function doorAction($cardId, $lockId): array
    {
        $this->lock = Lock::find($lockId);
        if (Add_lock::first()->status && !$this->lock) {
                Lock::create(['id' => $lockId]);
                return ['code' => 3];
        }

        if (!$lockId || !$this->isValidAction()) {
            return ['code' => 0];
        }

        $card = Card::where('uid', $cardId)->first();
        if (!$card) {
            return ['code' => 2];
        }
        $this->cardId = $cardId;
        $door = $this->lock->door;

        switch ($this->action) {
            case 'lock':
                return $this->lockDoor($door);
            case 'unlock':
                return $this->unlockDoor($card, $door);
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

    private function unlockDoor($card, $door): array
    {
        $second = Carbon::createFromFormat('H:i:s', $door->unlock_duration)->secondsSinceMidnight();
        if ($door->owner) {
            if ($this->lock->time_end > now()->timestamp) {
                return ['code' => '0', 'error' => 'Action repeat'];
            }
        }
        if ($card->level >= $door->level) {
            $door->update(['owner' => $card->id]);
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
            'action' => $action,
            'card_id' => $this->cardId,
            'door_id' => $this->lock->door_id,
        ]);
    }
}

