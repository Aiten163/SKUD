<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsMultiSource;

class DoorLog extends Model
{
    use HasFactory;
    use Filterable;
    use AsMultiSource;
    protected $fillable = ['door_id', 'card_id', 'time', 'action'];
    protected $allowedFilters=[
        'door_id'=> Where::class,
        'card_id'=> Where::class,
        'action'=> Where::class,
        'time'=> Where::class
    ];
    public $timestamps = false;
    public function door(): BelongsTo
    {
        return $this->belongsTo(Door::class);
    }
    public function card(): BelongsTo
    {
        return $this->belongsTo(Door::class);
    }

}
