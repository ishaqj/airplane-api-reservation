<?php

namespace App\Providers;

use App\Services\TicketService;
use App\Services\TicketServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            TicketServiceInterface::class,
            TicketService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
