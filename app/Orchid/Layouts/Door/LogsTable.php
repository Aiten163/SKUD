<?php

namespace App\Orchid\Layouts\Door;

use App\Models\Door;
use Carbon\Carbon;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
class LogsTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'logs';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->sort()->filter(TD::FILTER_NUMERIC),
            TD::make('action', 'Действие')->sort()->filter(),
            TD::make('door_id', 'ID двери')->sort()->filter(),
            TD::make('card_id', 'ID карточки')->sort()->filter(),
            TD::make('room', 'Номер комнаты')->filter()
                ->render(function ($log) {
                    if (empty($log->door->room)) {
                        return;
                    } else {
                        return $log->door->room;
                    }
                }
                ),
            TD::make('building', 'Корпус')->filter()
                ->render(function ($log) {
                    if (empty($log->door->building)) {
                        return;
                    } else {
                        return $log->door->building;
                    }
                }
                ),
        ];
    }
}
