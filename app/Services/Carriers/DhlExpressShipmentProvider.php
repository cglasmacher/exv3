<?php
namespace App\Services\Carriers;

use App\Models\Carrier;
use App\Models\Order;
use App\Models\Label;
use App\Models\OrderCarrierResponse;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DhlExpressShipmentProvider implements CarrierShipmentProviderInterface
{
    protected Client $http;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 20]);
    }

    public function createShipment(Carrier $carrier, Order $order): void
    {
        $url = $carrier->endpoints['ship'] ?? env('DHL_EXPRESS_SHIP_URL');
        $payload = [
            'plannedShippingDateAndTime' => $order->placed_at->toIso8601String(),
            'pickup' => [
                'address' => [
                    'postalCode'=> $order->pickupAddress->postcode,
                    'cityName'  => $order->pickupAddress->city,
                    'countryCode'=> $order->pickupAddress->country,
                ],
                'date' => $order->preferred_pickup_date,
                'time' => $order->preferred_pickup_time,
            ],
            'shipmentNotification' => [
                'receiverEmailAddress' => $order->user->email,
            ],
            'exportDeclaration' => [
                'sellorTaxNumber' => $order->shipper->tax_number,
                'lineItems' => $order->cart->items->map(fn($item) => [
                    'description' => $item->quote->service->name,
                    'quantity'    => $item->quantity,
                    'weight'      => $item->quote->price,
                ])->toArray(),
            ],
            'packages' => $order->cart->items->map(fn($item) => [
                'weight' => $item->quote->price,
                'dimensions'=> [
                    'length'=> $item->quote->service->pricingRule->max_length,
                    'width'=> $item->quote->service->pricingRule->max_width,
                    'height'=> $item->quote->service->pricingRule->max_height,
                ],
            ])->toArray(),
        ];

        try {
            $resp = $this->http->post($url, [
                'headers' => ['DHL-API-Key'=> $carrier->api_key,'Content-Type'=>'application/json'],
                'json'    => $payload,
            ]);
            $data = json_decode($resp->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            Log::error("DHL Express shipment error: {$e->getMessage()}");
            return;
        }

        // Store raw response
        OrderCarrierResponse::create([
            'order_id'   => $order->id,
            'carrier_id' => $carrier->id,
            'payload'    => $data,
        ]);

        // Label
        Label::create([
            'order_id'           => $order->id,
            'external_label_url' => $data['labels'][0]['labelUrl'] ?? null,
        ]);
    }
}