<?php

namespace App\Services;

use App\Data\TicketData;
use App\Exceptions\ExistingTicketException;
use App\Exceptions\SeatException;
use App\Exceptions\TicketStatusException;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Ticket;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Throwable;

class TicketService implements TicketServiceInterface
{
    /**
     * @throws ExistingTicketException
     * @throws SeatException|Throwable
     */
    public function createTicket(TicketData $ticketData): Ticket
    {
        return DB::transaction(function () use ($ticketData) {
            $flight = Flight::updateOrCreate([
                'flight_number' => $ticketData->flightNumber,
                'source' => $ticketData->sourceAirport,
                'destination_airport' => $ticketData->destinationAirport,
            ], [
                'departure_time' => $ticketData->departureTime,
            ]);
            $passenger = Passenger::firstOrCreate([
                'passport_id' => $ticketData->passportId,
            ]);

            $existingTicket = Ticket::where('flight_id', $flight->id)
                ->where('passenger_id', $passenger->id)
                ->exists();

            if ($existingTicket) {
                throw new ExistingTicketException('A ticket already exists for this flight and passenger.');
            }

            return Ticket::create([
                'seat' => $this->generateSeat($flight),
                'status' => Ticket::STATUS_ACTIVE,
                'flight_id' => $flight->id,
                'passenger_id' => $passenger->id,
            ]);

        });
    }

    /**
     * @throws TicketStatusException
     */
    public function cancelTicket(Ticket $ticket): Ticket
    {
        $ticket->cancel();
        $ticket->save();

        return $ticket;
    }

    /**
     * @throws Throwable
     */
    public function changeSeat(Ticket $ticket): Ticket
    {
        return DB::transaction(function () use ($ticket) {
            $newSeat = $this->generateSeat($ticket->flight);
            $ticket->seat = $newSeat;
            $ticket->save();

            return $ticket;
        });
    }



    /**
     * @throws SeatException
     */
    private function generateSeat(?Flight $flight): string
    {
        if ($flight === null) {
            throw new InvalidArgumentException('Flight cannot be null.');
        }

        $allSeats = collect(['A', 'B', 'C', 'D'])->map(function (string $row): Collection {
            return collect(range(1, 32))->map(function (int $number) use ($row): string {
                return "{$row}{$number}";
            });
        })->flatten();
        $occupiedSeats = $flight->activeTickets()->pluck('seat');
        /** @var Collection<int, string> $availableSeats */
        $availableSeats = $allSeats->diff($occupiedSeats);

        if ($availableSeats->isEmpty()) {
            throw new SeatException('No available seats for this flight.');
        }

        return $availableSeats->random();
    }
}
