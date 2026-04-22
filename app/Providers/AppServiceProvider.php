<?php

namespace App\Providers;

use App\Policies\V1\TicketPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('v1.ticket.update', [TicketPolicy::class, 'update']);
        Gate::define('v1.ticket.destroy', [TicketPolicy::class, 'destroy']);
        Gate::define('v1.ticket.store', [TicketPolicy::class, 'store']);
        Gate::define('v1.ticket.replace', [TicketPolicy::class, 'replace']);
    }
}
