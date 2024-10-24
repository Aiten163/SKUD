<?php

namespace App\Orchid\Screens;

use App\Models\Door;
use App\Models\Lock;
use App\Orchid\Layouts\Door\DoorTable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Orchid\Screen\Fields\Select;
use \Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast as FacadesToast;

class DoorScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'doors'=>door::filters()->defaultSort('id')->paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Заказы';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            ModalToggle::make("Добавить дверь")->modal('createdoor')->method('create'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return
            [
                doorTable::class,
                Layout::modal('createdoor', Layout::rows([
                    Input::make('id')->required()->title('ID'),
                    Input::make('level')->required()->title('Уровень'),
                    Input::make('build')->required()->title('Корпус'),
                    Input::make('number')->required()->title('Кабинет №'),
                    Input::make('owner')->type('email')->title('Владелец'),
                    Select::make('lock_id')->fromModel(Lock::class, 'name')->title('Услуга'),
                ]))->title("Добавить дверь")->applyButton('Добавить'),

                Layout::modal("editdoor", Layout::rows
                (
                    [
                        Input::make('door.id')->type('hidden'),
                        Input::make('door.level')->required()->title("Имя"),
                        Input::make('door.build')->required()->title("Фамилия"),
                        Input::make('door.owner')->required()->title('Адрес'),
                        Input::make('door.number')->required()->title('Телефон')->mask('99999999999'),
                        Input::make('door.email')->type('email')->title('Почта'),
                        Select::make('door.lock_id')->fromModel(Service::class, 'name')->title('Услуга'),
                        Input::make('door.note')->title('Заметка'),
                        Input::make('door.status')->title('Статус')
                    ]
                ))->async('asyncGetdoor')
            ];
    }
    public function asyncGetdoor(door $door): array
    {
        return[
            'door' => $door
        ];
    }
    public function update(Request $request)
    {
        door::find($request->input('door.id'))->update($request->door);
        Toast::info('Успешно обновлено');
    }
    public function delete(Request $request)
    {
        door::find($request->door)->delete();
        Toast::info('Успешно удалено');
    }


    public function create(Request $request): void
    {
        $request->validate([
            'number'=> ['required'],
            'name'=> ['required'],
            'surename'=> ['required'],
            'adress'=> ['required'],
            'service_id'=> ['required'],
        ]);
        door::create($request->merge([
        ])->except('_token'));
        FacadesToast::info('Заказ успешно добавлен');
    }
}
