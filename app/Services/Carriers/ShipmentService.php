<?php
namespace App\Services\Carriers;

use App\Models\Carrier;
use App\Models\Order;
use App\Services\Carriers\CarrierShipmentProviderInterface;

class ShipmentService
{
    protected CarrierShipmentProviderInterface $defaultProvider;

    public function __construct(CarrierShipmentProviderInterface $defaultProvider)
    {
        $this->defaultProvider = $defaultProvider;
    }

    public function createLabels(Order $order): void
    {
        $carriers = Carrier::whereIn('id', $order->cart->items->pluck('quote.service.carrier_id'))->get();

        foreach ($carriers as $carrier) {
            $providerClass = 'App\\Services\\Carriers\\'.ucfirst(camel_case($carrier->slug)).'ShipmentProvider';
            $provider = class_exists($providerClass) ? app($providerClass) : $this->defaultProvider;
            $provider->createShipment($carrier, $order);
        }
    }
}