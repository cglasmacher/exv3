<?php
namespace App\Services\Carriers;

use App\Models\Carrier;
use App\Models\Order;
use App\Models\Label;
use App\Models\OrderCarrierResponse;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FedexShipmentProvider implements CarrierShipmentProviderInterface
{
    protected Client $http;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 20]);
    }

    public function createShipment(Carrier $carrier, Order $order): void
    {
        $url = $carrier->endpoints['ship'] ?? env('FEDEX_SHIP_URL');
        $payload = [
            'accountNumber'=> $carrier->api_key,
            'origin'=> [
                'postalCode'=> $order->pickupAddress->postcode,
                'countryCode'=> $order->pickupAddress->country,
            ],
            'destination'=> [
                'postalCode'=> $order->recipientAddress->postcode,
                'countryCode'=> $order->recipientAddress->country,
            ],
            'pickup'=> [
                'date'=> $order->preferred_pickup_date,
                'time'=> $order->preferred_pickup_time,
            ],
            'packages'=> $order->cart->items->map(fn($i)=>[
                'weight'=> $i->quote->price,
                'dimensions'=> [
                    'length'=> $i->quote->service->pricingRule->max_length,
                    'width'=>  $i->quote->service->pricingRule->max_width,
                    'height'=> $i->quote->service->pricingRule->max_height,
                ],
            ])->toArray(),
        ];

        try {
            $resp = $this->http->post($url, [
                'headers'=> ['Content-Type'=> 'application/json'],
                'auth'=> [$carrier->api_key, $carrier->api_secret],
                'json'=> $payload,
            ]);
            $data = json_decode($resp->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            Log::error("FedEx shipment error: {$e->getMessage()}");
            return;
        }

        OrderCarrierResponse::create([
            'order_id'=> $order->id,
            'carrier_id'=> $carrier->id,
            'payload'=> $data,
        ]);

        $labelUrl = data_get($data, 'rateReplyDetails.0.ratedShipmentDetails.0.shipmentRateDetail.totalNetCharge');
        Label::create([
            'order_id'=> $order->id,
            'external_label_url'=> $labelUrl,
        ]);
    }
}