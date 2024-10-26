<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsMultiSource;

/**
 *
 *
 * @property int $id
 * @property int|null $level
 * @property string|null $sha
 * @method static \Database\Factories\CardFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereSha($value)
 * @mixin \Eloquent
 */
class Card extends Model
{
    use HasFactory;
    use Filterable;
    use AsMultiSource;

    public $timestamps = false;
    protected $fillable =
        [
            'id',
            'level',
            'uid',
            'msru_id'
        ];
    protected $allowedFilters=[
        'id'=> Where::class,
        'level'=> Where::class,
        'uid'=> Where::class,
        'msru_id'=> Where::class,
    ];
}
