<?php

namespace App\Orchid\Layouts\Door;

use App\Models\Door;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
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
            TD::make('owner', 'Владелец')->sort()->filter(),
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
