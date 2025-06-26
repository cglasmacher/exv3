<?php
namespace App\Jobs;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOrderJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected int $orderId;

    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    public function handle(OrderService $orderService): void
    {
        $order = Order::findOrFail($this->orderId);
        $orderService->processOrder($order);
    }
}