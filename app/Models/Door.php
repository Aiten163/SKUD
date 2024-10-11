<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
