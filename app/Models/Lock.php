<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsMultiSource;

/**
 * 
 *
 * @property int $id
 * @property int|null $door_id
 * @property-read \App\Models\Door|null $door
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock defaultSort(string $column, string $direction = 'asc')
 * @method static \Database\Factories\LockFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock filters(?mixed $kit = null, ?\Orchid\Filters\HttpFilter $httpFilter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock filtersApply(iterable $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock filtersApplySelection($class)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock whereDoorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lock whereId($value)
 * @mixin \Eloquent
 */
class Lock extends Model
{
    use HasFactory;
    use Filterable;
    use AsMultiSource;
    protected $fillable = ['door_id'];
    protected $allowedFilters=[
        'door_id'=> Where::class,
        'id'=> Where::class
    ];
    public $timestamps = false;
    public function door(): BelongsTo
    {
        return $this->belongsTo(Door::class);
    }
}
