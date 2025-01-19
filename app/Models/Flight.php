<?php

namespace App\Models;

use Database\Factories\FlightFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $flight_number
 * @property string $departure_time
 * @property string $source
 * @property string $destination_airport
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Ticket> $tickets
 * @property-read int|null $tickets_count
 *
 * @method static \Database\Factories\FlightFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereDepartureTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereDestinationAirport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereFlightNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Flight whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Ticket> $activeTickets
 * @property-read int|null $active_tickets_count
 *
 * @mixin \Eloquent
 */
class Flight extends Model
{
    /** @use HasFactory<FlightFactory> */
    use HasFactory;

    protected $fillable = [
        'flight_number',
        'departure_time',
        'source',
        'destination_airport',
    ];

    /** RELATIONS */

    /**
     * Get the active tickets for the flight.
     *
     * @return HasMany<Ticket, $this>
     */
    public function activeTickets(): HasMany
    {
        return $this->hasMany(Ticket::class)->where('status', Ticket::STATUS_ACTIVE);
    }
}
