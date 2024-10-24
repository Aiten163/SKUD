<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsMultiSource;

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
            'build',
            'owner',
            'number'
        ];
    protected $allowedFilters=[
        'level'=> Where::class,
        'id'=> Where::class,
        'build'=> Where::class,
        'number'=> Where::class,
        'owner'=> Where::class
    ];
    public function lock(): HasOne
    {
        return $this->hasOne(Lock::class);
    }
}
