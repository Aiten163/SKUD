<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Add_lock extends Model
{
    use HasFactory;
    protected $fillable =
        [
            'status'
        ];
}
