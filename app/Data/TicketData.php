<?php

namespace App\Data;

use Illuminate\Support\Carbon;

class TicketData
{
    public function __construct(
        public string $flightNumber,
        public Carbon $departureTime,
        public string $sourceAirport,
        public string $destinationAirport,
        public string $passportId) {}
}
