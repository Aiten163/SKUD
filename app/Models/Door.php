<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsMultiSource;

/**
 *
 *
 * @property int $id
 * @property int|null $number
 * @property int|null $build
 * @property int|null $owner
 * @property int|null $level
 * @property-read \App\Models\Lock|null $lock
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Door defaultSort(string $column, string $direction = 'asc')
 * @method static \Database\Factories\DoorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Door filters(?mixed $kit = null, ?\Orchid\Filters\HttpFilter $httpFilter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Door filtersApply(iterable $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Door filtersApplySelection($class)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Door newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Door newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Door query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Door whereBuild($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Door whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Door whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Door whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Door whereOwner($value)
 * @mixin \Eloquent
 */
class Door extends Model
{
    use HasFactory;
    use Filterable;
    use AsMultiSource;
    public $timestamps = false;
    protected $fillable =
        [
            'id',
            'level',
            'building',
            'owner',
            'room',
            'warn_duration',
            'unlock_duration'
        ];
    protected $allowedFilters=[
        'level'=> Where::class,
        'id'=> Where::class,
        'building'=> Where::class,
        'room'=> Where::class,
        'owner'=> Where::class,
        'warn_duration'=> Where::class,
        'unlock_duration' =>Where::class
    ];
    public function doorLock(): HasOne
    {
        return $this->hasOne(Lock::class);
    }
}
