<?php

use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:60,1')->group(function (): void {
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::patch('/tickets/{ticket}/cancel', [TicketController::class, 'cancel'])->name('tickets.cancel');
    Route::patch('/tickets/{ticket}/seat', [TicketController::class, 'changeSeat'])->name('tickets.changeSeat');
});
