<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\QuoteRequestCreated;
use App\Listeners\SendQuoteResponseNotification;
use App\Events\OrderProcessed;
use App\Listeners\SendOrderProcessedNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        QuoteRequestCreated::class => [
            SendQuoteResponseNotification::class,
        ],
        OrderProcessed::class => [
            SendOrderProcessedNotification::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}