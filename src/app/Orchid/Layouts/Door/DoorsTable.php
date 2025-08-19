<?php

namespace App\Orchid\Layouts\Door;

use App\Models\Door;
use Carbon\Carbon;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
class DoorsTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'doors';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->sort()->filter(TD::FILTER_NUMERIC),
            TD::make('room', 'Номер двери')->sort()->filter(),
            TD::make('building', 'Корпус')->sort()->filter(),
            TD::make('level', 'Уровень')->sort()->filter(),
            TD::make('owner', 'Владелец')->sort()->filter(),
            TD::make('time', 'Время занятия/предупреждения')
                ->render(function ($door) {
                    // Преобразуем значения в объекты Carbon, чтобы использовать форматирование
                    $unlockTime = Carbon::createFromFormat('H:i:s', $door->unlock_duration)->format('H:i:s');
                    $warnTime = Carbon::createFromFormat('H:i:s', $door->warn_duration)->format('H:i:s');

                    return "<span style='line-height: 1.2;'>{$unlockTime}<br>{$warnTime}</span>";
                }),
            TD::make('action', '')->cantHide()->render(function (Door $Door)
            {
                return ModalToggle::make("")
                    ->modal('editdoor')
                    ->icon('pen')
                    ->method('update')
                    ->modalTitle('Редактирование двери '.$Door->id.$Door->room)
                    ->asyncParameters([
                            'door' =>$Door->id
                        ]
                    );
            }),
            TD::make('action','')->cantHide()
                ->render(function (Door $Door)
                {
                    return Button::make("")
                        ->icon('trash')
                        ->method('delete',[
                            'door'=>$Door->id
                        ]);
                })
        ];
    }
}
