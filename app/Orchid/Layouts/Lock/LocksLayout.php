<?php

namespace App\Orchid\Layouts\Lock;

use App\Models\Lock;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
class LockTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'Locks';

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
                ->render(function ($Lock)
                {
                    return $Lock->service->name;
                }
                ),
            TD::make('note', 'Комментарий'),
            TD::make('status', 'Статус')->sort(),
            TD::make('created_at', 'Дата создания')->defaultHidden()->sort(),
            TD::make('action', '')->cantHide()->render(function (Lock $Lock)
            {
                return ModalToggle::make("")
                    ->modal('editLock')
                    ->icon('pen')
                    ->method('update')
                    ->modalTitle('Редактирование заказа '.$Lock->name.$Lock->surename)
                    ->asyncParameters([
                            'Lock' =>$Lock->id
                        ]
                    );
            }),
            TD::make('action','')->cantHide()
                ->render(function (Lock $Lock)
                {
                    return Button::make("")
                        ->icon('trash')
                        ->method('delete',[
                            'Lock'=>$Lock->id
                        ]);
                })
        ];
    }
}
