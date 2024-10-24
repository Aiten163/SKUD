<?php

namespace App\Orchid\Screens;

use App\Models\Lock;
use App\Models\Service;
use App\Orchid\Layouts\Lock\LockTable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Orchid\Screen\Fields\Select;
use \Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast as FacadesToast;

class LocksScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'Locks'=>Lock::filters()->defaultSort('id')->paginate()
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
            ModalToggle::make("Добавить заказ")->modal('createLock')->method('create'),
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
                LockTable::class,
                Layout::modal('createLock', Layout::rows([
                    Input::make('name')->required()->title('Имя'),
                    Input::make('surename')->required()->title('Фамилия'),
                    Input::make('adress')->required()->title('Адрес'),
                    Input::make('number')->required()->title('Телефон')->mask('99999999999'),
                    Input::make('email')->type('email')->title('Почта'),
                    Select::make('service_id')->fromModel(Service::class, 'name')->title('Услуга'),
                    Input::make('note')->title('Заметка'),
                ]))->title("Добавить заказ")->applyButton('Добавить'),
                Layout::modal("editLock", Layout::rows
                (
                    [
                        Input::make('Lock.id')->type('hidden'),
                        Input::make('Lock.name')->required()->title("Имя"),
                        Input::make('Lock.surename')->required()->title("Фамилия"),
                        Input::make('Lock.adress')->required()->title('Адрес'),
                        Input::make('Lock.number')->required()->title('Телефон')->mask('99999999999'),
                        Input::make('Lock.email')->type('email')->title('Почта'),
                        Select::make('Lock.service_id')->fromModel(Service::class, 'name')->title('Услуга'),
                        Input::make('Lock.note')->title('Заметка'),
                        Input::make('Lock.status')->title('Статус')
                    ]
                ))->async('asyncGetLock')
            ];
    }
    public function asyncGetLock(Lock $Lock): array
    {
        return[
            'Lock' => $Lock
        ];
    }
    public function update(Request $request)
    {
        Lock::find($request->input('Lock.id'))->update($request->Lock);
        Toast::info('Успешно обновлено');
    }
    public function delete(Request $request)
    {
        Lock::find($request->lock)->delete();
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
        Lock::create($request->merge([
        ])->except('_token'));
        FacadesToast::info('Заказ успешно добавлен');
    }
}
