<?php
namespace App\Services;

use App\Models\Order;
use App\Models\Cart;
use App\Services\PaymentService;
use App\Services\ShipmentService;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected PaymentService $payment;
    protected ShipmentService $shipment;

    public function __construct(PaymentService $payment, ShipmentService $shipment)
    {
        $this->payment  = $payment;
        $this->shipment = $shipment;
    }

    /**
     * Process an order: execute payment, generate shipment labels
     */
    public function processOrder(Order $order): Order
    {
        DB::transaction(function () use ($order) {
            // 1. Execute payment
            $payment = $this->payment->pay(
                $order,
                $order->payment_provider,
                $order->user_payment_method_id
            );

            // 2. Generate shipment labels for each carrier/service
            $this->shipment->createLabels($order);

            // 3. Update order status
            $order->update(['status' => 'paid', 'placed_at' => now()]);
        });

        return $order->fresh();
    }
}