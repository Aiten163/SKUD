<?php

namespace App\Orchid\Screens;

use App\Models\Door;
use App\Models\Lock;
use App\Orchid\Layouts\Door\DoorsTable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Orchid\Screen\Fields\Select;
use \Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast as FacadesToast;

class DoorsScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'doors'=>Door::filters()->defaultSort('id')->paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Двери';
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
                DoorsTable::class,
                Layout::modal('createdoor', Layout::rows([
                    Input::make('level')->required()->title('Уровень'),
                    Input::make('building')->required()->title('Корпус'),
                    Input::make('room')->required()->title('Номер двери'),
                    Input::make('owner')->type('email')->title('Владелец'),
                    Select::make('lock_id')->fromModel(Lock::class, 'id')->title('Замок'),
                ]))->title("Добавить дверь")->applyButton('Добавить'),

                Layout::modal("editdoor", Layout::rows
                (
                    [
                        Input::make('door.id')->type('hidden'),
                        Input::make('door.level')->required()->title("Уровень"),
                        Input::make('door.building')->required()->title("Корпус"),
                        Input::make('door.owner')->required()->title('Владелец'),
                        Input::make('door.room')->required()->title('Номер двери'),
                    ]
                ))->async('asyncGetDoor')
            ];
    }
    public function asyncGetDoor(door $door): array
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
            'room'=> ['required'],
            'level'=> ['required'],
            'lock_id'=> ['required'],
            'owner'=> ['required'],
        ]);
        door::create($request->merge([
        ])->except('_token'));
        FacadesToast::info('Дверь успешно добавлена');
    }
}
