<?php

namespace App\Orchid\Screens;

use App\Models\DoorLog;
use App\Orchid\Layouts\Door\LogsTable;
use App\Orchid\Layouts\Lock\LocksTable;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast as FacadesToast;

class LogsDoorScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'logs' => DoorLog::filters()->defaultSort('id')->paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Логи';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {

        return [
            Button::make('Очистить логи')
                ->method('clearLogs')
                ->style('color:red; font-size:19px')
                ->icon('book')
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
                LogsTable::class
            ];
    }

    public function clearLogs()
    {
        DoorLog::truncate();
        FacadesToast::info('Логи очищены');
    }
}
