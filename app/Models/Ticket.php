<?php

namespace App\Models;

use App\Exceptions\TicketStatusException;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string|null $seat
 * @property string $status
 * @property int $flight_id
 * @property int $passenger_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Flight|null $flight
 * @property-read \App\Models\Passenger|null $passenger
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket active()
 * @method static \Database\Factories\TicketFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereFlightId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket wherePassengerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereSeat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Ticket extends Model
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory;

    protected $fillable = [
        'seat',
        'status',
        'flight_id',
        'passenger_id',
    ];

    const STATUS_ACTIVE = 'ACTIVE';

    const STATUS_CANCELLED = 'CANCELLED';

    /** RELATIONS */
    /**
     * @return BelongsTo<Flight, $this>
     */
    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }

    /**
     * @return BelongsTo<Passenger, $this>
     */
    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class);
    }

    /** PUBLIC METHODS **/

    /**
     * @throws TicketStatusException
     */
    public function cancel(): void
    {
        if ($this->status === self::STATUS_CANCELLED) {
            throw new TicketStatusException('Ticket is already cancelled.');
        }

        $this->status = self::STATUS_CANCELLED;
        $this->seat = null;
    }
}
