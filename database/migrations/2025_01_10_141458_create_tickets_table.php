<?php

use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Ticket;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('seat')->nullable()->default(null);
            $table->string('status')->default(Ticket::STATUS_ACTIVE);
            $table->foreignIdFor(Flight::class);
            $table->foreignIdFor(Passenger::class);
            $table->timestamps();
            $table->unique(['flight_id', 'seat'], 'flight_seat_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
