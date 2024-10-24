<?php

namespace App\Orchid\Layouts\Door;

use App\Models\Door;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
class DoorTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'Doors';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'Номер заказа')->sort()->filter(TD::FILTER_NUMERIC),
            TD::make('name', 'Имя')->sort()->filter(),
            TD::make('surename', 'Фамилия')->sort()->filter(),
            TD::make('adress', 'Адрес')->sort()->filter(TD::FILTER_TEXT),
            TD::make('number', 'Номер')->filter(TD::FILTER_TEXT),
            TD::make('email', 'Почта')->filter(TD::FILTER_TEXT),
            TD::make('service', 'Услуга')->filter(TD::FILTER_TEXT)
                ->render(function ($Door)
                {
                    return $Door->service->name;
                }
                ),
            TD::make('note', 'Комментарий'),
            TD::make('status', 'Статус')->sort(),
            TD::make('created_at', 'Дата создания')->defaultHidden()->sort(),
            TD::make('action', '')->cantHide()->render(function (Door $Door)
            {
                return ModalToggle::make("")
                    ->modal('editDoor')
                    ->icon('pen')
                    ->method('update')
                    ->modalTitle('Редактирование заказа '.$Door->name.$Door->surename)
                    ->asyncParameters([
                            'Door' =>$Door->id
                        ]
                    );
            }),
            TD::make('action','')->cantHide()
                ->render(function (Door $Door)
                {
                    return Button::make("")
                        ->icon('trash')
                        ->method('delete',[
                            'Door'=>$Door->id
                        ]);
                })
        ];
    }
}
