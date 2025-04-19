<?php

namespace App\Orchid\Screens;

use App\Models\Add_lock;
use App\Models\Door;
use App\Models\Lock;
use App\Orchid\Layouts\Lock\LocksTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Orchid\Screen\Actions\Button;
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
            'locks'=>Lock::filters()->defaultSort('id')->paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Замки';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        $add_lock = Add_lock::first();
        if(empty($add_lock->status))
        {
            return [
                Button::make('Режим привязки выключен')
                    ->method('change_add_lock' )
                    ->style('color:red; font-size:19px')
                    ->icon('lock'),
            ];
        } else {
            return [
                Button::make('Режим привязки включен')
                    ->method('change_add_lock')
                    ->style('color:green; font-size:19px')
                    ->icon('unlock'),
            ];
        }
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
                LocksTable::class,
                Layout::modal('createlock', Layout::rows([
                    Input::make('door_id')->required()->title('ID двери'),
                ]))->title("Добавить замок")->applyButton('Добавить'),

                Layout::modal("editlock", Layout::rows
                (
                    [
                        Input::make('lock.id')->type('hidden'),
                        Input::make('lock.door_id')->title("ID двери"),
                    ]
                ))->async('asyncGetLock')
            ];
    }
    public function asyncGetLock(lock $lock): array
    {
        return[
            'lock' => $lock
        ];
    }
    public function update(Request $request)
    {
        lock::find($request->input('lock.id'))->update($request->lock);
        Toast::info('Успешно обновлено');
    }
    public function delete(Request $request)
    {
        lock::find($request->lock)->delete();
        Toast::info('Успешно удалено');
    }


    public function create(Request $request): void
    {

        door::create($request->merge([
        ])->except('_token'));
        FacadesToast::info('Замок успешно добавлен');
    }

    public function change_add_lock()
    {
        $add_lock = Add_lock::first();
        $add_lock->status = !$add_lock->status;
        $add_lock->save();
    }
}
