<?php

namespace App\Services;

use App\Data\TicketData;
use App\Models\Ticket;

interface TicketServiceInterface
{
    public function createTicket(TicketData $ticketData): Ticket;

    public function cancelTicket(Ticket $ticket): Ticket;

    public function changeSeat(Ticket $ticket): Ticket;
}
