<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Door extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable =
        [
            'id',
            'level',
            'build',
            'owner',
            'number'
        ];
    public function lock(): HasOne
    {
        return $this->hasOne(Lock::class);
    }
}
