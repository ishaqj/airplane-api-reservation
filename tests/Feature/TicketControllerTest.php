<?php

namespace Tests\Feature;

use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_ticket(): void
    {
        $data = [
            'flight_number' => $flightNumber = 'SK123',
            'departure_time' => $departureTime = now()->addDays(7)->toDateTimeString(),
            'source' => $source = 'Arlanda Airport',
            'destination_airport' => $destinationAirport = 'London Heathrow Airport',
            'passport_id' => $passportId = 'A12345678',
        ];

        $response = $this->postJson(route('tickets.store'), $data);

        $response->assertStatus(201);
        $response->assertJson([
            'flight_number' => $flightNumber,
            'source' => $source,
            'destination_airport' => $destinationAirport,
            'departure_time' => $departureTime,
            'passport_id' => Str::mask($passportId, '*', 3),
            'status' => Ticket::STATUS_ACTIVE,
        ]);
        $this->assertNotEmpty($response->json('seat'));

    }

    public function test_cant_create_duplicate_ticket(): void
    {
        $data = [
            'flight_number' => 'SK123',
            'departure_time' => now()->addDays(7)->toDateTimeString(),
            'source' => 'Arlanda Airport',
            'destination_airport' => 'London Heathrow Airport',
            'passport_id' => 'A12345678',
        ];

        $response = $this->postJson(route('tickets.store'), $data);

        $response->assertStatus(201);

        $response2 = $this->postJson(route('tickets.store'), $data);

        $response2->assertStatus(400);
        $response2->assertJsonFragment(['error' => 'A ticket already exists for this flight and passenger.']);
    }

    public function test_can_cancel_ticket(): void
    {
        $ticket = Ticket::factory()->for(Flight::factory()->create([
            'flight_number' => $flightNumber = 'SK123',
            'departure_time' => $departureTime = now()->addDays(7)->toDateTimeString(),
            'source' => $source = 'Arlanda Airport',
            'destination_airport' => $destinationAirport = 'London Heathrow Airport',
        ]))->for(Passenger::factory()->create([
            'passport_id' => $passportId = 'A12345678',
        ]))->create([
            'seat' => 'A1',
        ]);

        $response = $this->patchJson(route('tickets.cancel', $ticket->id));

        $response->assertStatus(200);
        $response->assertJson([
            'flight_number' => $flightNumber,
            'source' => $source,
            'destination_airport' => $destinationAirport,
            'departure_time' => $departureTime,
            'passport_id' => Str::mask($passportId, '*', 3),
            'seat' => null,
            'status' => Ticket::STATUS_CANCELLED,
        ]);
    }

    public function test_cant_cancel_cancelled_ticket(): void
    {
        $ticket = Ticket::factory()->for(Flight::factory()->create([
            'flight_number' => 'SK123',
            'departure_time' => now()->addDays(7)->toDateTimeString(),
            'source' => 'Arlanda Airport',
            'destination_airport' => 'London Heathrow Airport',
        ]))->for(Passenger::factory()->create([
            'passport_id' => 'A12345678',
        ]))->create([
            'seat' => null,
            'status' => Ticket::STATUS_CANCELLED,
        ]);

        $response = $this->patchJson(route('tickets.cancel', $ticket->id));

        $response->assertStatus(400);
        $response->assertJsonFragment(['error' => 'Ticket is already cancelled.']);
    }

    public function test_can_change_seat(): void
    {
        $ticket = Ticket::factory()->for(Flight::factory()->create([
            'flight_number' => $flightNumber = 'SK123',
            'departure_time' => $departureTime = now()->addDays(7)->toDateTimeString(),
            'source' => $source = 'Arlanda Airport',
            'destination_airport' => $destinationAirport = 'London Heathrow Airport',
        ]))->for(Passenger::factory()->create([
            'passport_id' => $passportId = 'A12345678',
        ]))->create([
            'seat' => $seat = 'A1',
        ]);

        $response = $this->patchJson(route('tickets.changeSeat', $ticket->id));

        $response->assertJson([
            'flight_number' => $flightNumber,
            'source' => $source,
            'destination_airport' => $destinationAirport,
            'departure_time' => $departureTime,
            'passport_id' => Str::mask($passportId, '*', 3),
            'status' => Ticket::STATUS_ACTIVE,
        ]);

        $this->assertNotEquals($seat, $response->json('seat'));
        $this->assertNotEmpty($response->json('seat'));
    }

    public function test_cant_change_seat_for_cancelled_ticket(): void
    {
        $ticket = Ticket::factory()->for(Flight::factory()->create([
            'flight_number' => 'SK123',
            'departure_time' => now()->addDays(7)->toDateTimeString(),
            'source' => 'Arlanda Airport',
            'destination_airport' => 'London Heathrow Airport',
        ]))->for(Passenger::factory()->create([
            'passport_id' => 'A12345678',
        ]))->create([
            'seat' => null,
            'status' => Ticket::STATUS_CANCELLED,
        ]);

        $response = $this->patchJson(route('tickets.changeSeat', $ticket->id));

        $response->assertStatus(400);
        $response->assertJsonFragment(['error' => 'Cannot change seat for a cancelled ticket.']);
    }
}
