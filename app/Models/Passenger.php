<?php

namespace App\Models;

use Database\Factories\PassengerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $passport_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\PassengerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passenger newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passenger newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passenger query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passenger whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passenger whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passenger wherePassportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passenger whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Passenger extends Model
{
    /** @use HasFactory<PassengerFactory> */
    use HasFactory;

    protected $fillable = [
        'passport_id',
    ];
}
