<?php
namespace App\Services\Carriers;

use App\Models\Carrier;
use App\Models\Order;
use App\Models\Label;
use App\Models\OrderCarrierResponse;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DpdShipmentProvider implements CarrierShipmentProviderInterface
{
    protected Client $http;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 20]);
    }

    public function createShipment(Carrier $carrier, Order $order): void
    {
        $url = $carrier->endpoints['ship'] ?? env('DPD_SHIP_URL');
        $payload = [
            'origin' => $order->pickupAddress->postcode,
            'destination' => $order->recipientAddress->postcode,
            'pickupDate' => $order->preferred_pickup_date,
            'pickupTime' => $order->preferred_pickup_time,
            'parcels' => $order->cart->items->map(fn($item) => [
                'weight' => $item->quote->price,
                'dimensions' => [
                    'length'=> $item->quote->service->pricingRule->max_length,
                    'width'=>  $item->quote->service->pricingRule->max_width,
                    'height'=> $item->quote->service->pricingRule->max_height,
                ],
            ])->toArray(),
        ];

        try {
            $resp = $this->http->post($url, [
                'headers'=>['Authorization'=>"Bearer {$carrier->api_key}"],
                'json'=> $payload,
            ]);
            $data = json_decode($resp->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            Log::error("DPD shipment error: {$e->getMessage()}");
            return;
        }

        OrderCarrierResponse::create([
            'order_id'=> $order->id,
            'carrier_id'=> $carrier->id,
            'payload'=> $data,
        ]);

        Label::create([
            'order_id'=> $order->id,
            'external_label_url'=> $data['labelUrl'] ?? null,
        ]);
    }
}