<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public $timestamps = false;
    protected $fillable =
        [
            'id',
            'level',
            'sha',
        ];
}
