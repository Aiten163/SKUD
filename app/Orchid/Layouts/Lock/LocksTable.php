<?php

namespace App\Orchid\Layouts\Lock;

use App\Models\Door;
use App\Models\Lock;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
class LocksTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'locks';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->sort()->filter(TD::FILTER_NUMERIC),
            TD::make('door_id', 'ID двери')->sort()->filter(),
            TD::make('room', 'Номер комнаты')->filter()
                ->render(function ($lock)
                {
                    return $lock->door->room;
                }
                ),
            TD::make('building', 'Корпус')->filter()
                ->render(function ($lock)
                {
                    return $lock->door->building;
                }
                ),
            TD::make('owner', 'Владелец')->filter()
                ->render(function ($lock)
                {
                    return $lock->door->owner;
                }
                ),
            TD::make('level', 'Уровень')->filter()
                ->render(function ($lock)
                {
                    return $lock->door->level;
                }
                ),
            TD::make('action', '')->cantHide()->render(function (Lock $Lock)
            {
                return ModalToggle::make("")
                    ->modal('editlock')
                    ->icon('pen')
                    ->method('update')
                    ->modalTitle('Редактирование замка '.$Lock->id)
                    ->asyncParameters([
                            'lock' =>$Lock->id
                        ]
                    );
            }),
            TD::make('action','')->cantHide()
                ->render(function (Lock $Lock)
                {
                    return Button::make("")
                        ->icon('trash')
                        ->method('delete',[
                            'lock'=>$Lock->id
                        ]);
                })
        ];
    }
}
