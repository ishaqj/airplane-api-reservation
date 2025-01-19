<?php

namespace App\Http\Resources;

use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @mixin Ticket
 */
class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array{id: int, flight_number: string, departure_time: string, source: string, destination_airport: string, passport_id: string, seat: string|null, status: string}
     */
    public function toArray(Request $request): array
    {
        /** @var Flight $flight */
        $flight = $this->flight;
        /** @var Passenger $passenger */
        $passenger = $this->passenger;

        return [
            'id' => $this->id,
            'flight_number' => $flight->flight_number,
            'departure_time' => $flight->departure_time,
            'source' => $flight->source,
            'destination_airport' => $flight->destination_airport,
            'passport_id' => Str::mask($passenger->passport_id, '*', 3),
            'seat' => $this->seat,
            'status' => $this->status,
        ];
    }
}
