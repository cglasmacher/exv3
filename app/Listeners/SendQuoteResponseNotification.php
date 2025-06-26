<?php
namespace App\Listeners;

use App\Events\QuoteRequestCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Notifications\Notification;

class SendQuoteResponseNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(QuoteRequestCreated $event): void
    {
        // Notify user that quotes are available
        $event->quoteRequest->user?->notify(
            new \App\Notifications\QuotesReadyNotification($event->quoteRequest)
        );
    }
}