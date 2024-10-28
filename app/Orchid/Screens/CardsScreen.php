<?php

namespace App\Orchid\Screens;

use App\Models\Card;
use App\Orchid\Layouts\Card\CardsTable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Orchid\Screen\Fields\Select;
use \Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast as FacadesToast;

class CardsScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'cards'=>Card::filters()->defaultSort('id')->paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Карточки';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            ModalToggle::make("Добавить карточку")->modal('createcard')->method('create'),
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
                CardsTable::class,
                Layout::modal('createcard', Layout::rows([
                    Input::make('level')->title('Уровень'),
                    Input::make('mrsu_id')->title('ID ЭИОС'),
                    Input::make('uid')->title('Шифр'),
                ]))->title("Добавить карточку")->applyButton('Добавить'),

                Layout::modal("editcard", Layout::rows
                (
                    [
                        Input::make('card.id')->type('hidden'),
                        Input::make('card.level')->title('Уровень'),
                        Input::make('card.mrsu_id')->title('ID ЭИОС'),
                        Input::make('card.uid')->title('Шифр'),
                    ]
                ))->async('asyncGetCard')
            ];
    }
    public function asyncGetCard(card $card): array
    {
        return[
            'card' => $card
        ];
    }
    public function update(Request $request)
    {
        card::find($request->input('card.id'))->update($request->card);
        Toast::info('Успешно обновлено');
    }
    public function delete(Request $request)
    {
        card::find($request->card)->delete();
        Toast::info('Успешно удалено');
    }


    public function create(Request $request): void
    {
        card::create($request->merge([
        ])->except('_token'));
        FacadesToast::info('Карточка успешно добавлена');
    }
}
