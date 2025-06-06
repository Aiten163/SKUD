<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Двери')
                ->icon('bs.door-closed')
                ->route('platform.doors')
                 ->title('Таблицы'),
            Menu::make('Замки')
                ->icon('bs.lock')
                ->route('platform.locks'),
            Menu::make('Карточки')
                ->icon('bs.credit-card')
                ->route('platform.cards'),
            Menu::make('Логи')
                ->icon('bs.card-list')
                ->route('platform.logs')
                ->title('Администрирование'),

            Menu::make('Auth')
                ->icon('bs.123')
                ->route('platform.auth'),
            Menu::make('Тест Websocket')
                ->icon('bs.broadcast-pin')
                ->route('platform.websocket'),

            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title('Персонал и права'),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),

        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
