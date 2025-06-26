<?php
namespace App\Listeners;

use App\Events\OrderProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderProcessedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderProcessed $event): void
    {
        // Notify user that order completed
        $event->order->user?->notify(
            new \App\Notifications\OrderCompletedNotification($event->order)
        );
    }
}