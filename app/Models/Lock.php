<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lock extends Model
{
    use HasFactory;
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
