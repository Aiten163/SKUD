<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Add_lock;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Get Started';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return '';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
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
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [

        ];
    }
    public function change_add_lock()
    {
        $add_lock = Add_lock::first();
        $add_lock->status = !$add_lock->status;
        $add_lock->save();
    }
}
