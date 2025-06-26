<?php
namespace App\Services\Carriers;

use App\Models\Carrier;
use App\Models\Order;

interface CarrierShipmentProviderInterface
{
    /**
     * Create shipment for a given carrier and order
     * Should generate labels and tracking details
     */
    public function createShipment(Carrier $carrier, Order $order): void;
}
