<?php

namespace App\Orchid\Screens;

use App\Models\Door;
use App\Models\Lock;
use App\Orchid\Layouts\Door\DoorsTable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Orchid\Screen\Fields\DateTimer;
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
                    Input::make('level')->title('Уровень'),
                    Input::make('building')->title('Корпус'),
                    Input::make('room')->title('Номер двери'),
                    Input::make('owner')->title('Владелец'),
                    DateTimer::make('unlock_duration')->title('Время занятия')
                        ->noCalendar()->format('H:i')->format24hr()->enableTime(),
                    DateTimer::make('warn_duration')->title('Время предупреждения')
                        ->noCalendar()->format('H:i')->format24hr()->enableTime(),
                    Select::make('lock_id')->fromModel(Lock::class, 'id')->title('Замок'),
                ]))->title("Добавить дверь")->applyButton('Добавить'),

                Layout::modal("editdoor", Layout::rows
                (
                    [
                        Input::make('door.id')->type('hidden'),
                        Input::make('door.level')->title("Уровень"),
                        Input::make('door.building')->title("Корпус"),
                        Input::make('door.owner')->title('Владелец'),
                        Input::make('door.room')->title('Номер двери'),
                        DateTimer::make('door.unlock_duration')->title('Время занятия')
                            ->noCalendar()->format('H:i')->format24hr()->enableTime(),
                        DateTimer::make('door.warn_duration')->title('Время предупреждения')
                            ->noCalendar()->format('H:i')->format24hr()->enableTime(),
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
        door::create($request->merge([
        ])->except('_token'));
        FacadesToast::info('Дверь успешно добавлена');
    }
}
