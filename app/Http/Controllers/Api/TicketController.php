<?php

namespace App\Http\Controllers\Api;

use App\Data\TicketData;
use App\Http\Requests\CreateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Services\TicketServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Throwable;

class TicketController
{
    public function __construct(protected TicketServiceInterface $ticketService) {}

    public function store(CreateTicketRequest $request): JsonResponse
    {
        try {
            /** @var array{flight_number: string, departure_time: string, source: string, destination_airport: string, passport_id: string} $validatedReq */
            $validatedReq = $request->validated();
            $ticketData = new TicketData(
                flightNumber: $validatedReq['flight_number'],
                departureTime: Carbon::parse($validatedReq['departure_time']),
                sourceAirport: $validatedReq['source'],
                destinationAirport: $validatedReq['destination_airport'],
                passportId: $validatedReq['passport_id'],
            );

            $ticket = $this->ticketService->createTicket($ticketData);
            $ticket->load('flight', 'passenger');

            return response()->json(new TicketResource($ticket), 201);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function cancel(Ticket $ticket): JsonResponse
    {
        try {
            $ticket = $this->ticketService->cancelTicket($ticket);
            $ticket->load('flight', 'passenger');

            return response()->json(new TicketResource($ticket));
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function changeSeat(Ticket $ticket): JsonResponse
    {
        try {
            $ticket = $this->ticketService->changeSeat($ticket);
            $ticket->load('flight', 'passenger');

            return response()->json(new TicketResource($ticket));
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
