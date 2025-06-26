<?php
namespace App\Services\Carriers;

use App\Models\Carrier;
use App\Models\Order;
use App\Models\Label;
use App\Models\OrderCarrierResponse;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class UpsShipmentProvider implements CarrierShipmentProviderInterface
{
    protected Client $http;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 20]);
    }

    public function createShipment(Carrier $carrier, Order $order): void
    {
        $url = $carrier->endpoints['ship'] ?? env('UPS_SHIP_URL');
        $payload = [
            'ShipmentRequest' => [
                'Shipper' => [
                    'Address' => [
                        'PostalCode'=> $order->pickupAddress->postcode,
                        'CountryCode'=> $order->pickupAddress->country,
                    ],
                ],
                'ShipTo' => [
                    'Address' => [
                        'PostalCode'=> $order->recipientAddress->postcode,
                        'CountryCode'=> $order->recipientAddress->country,
                    ],
                ],
                'PickupDateInfo' => [
                    'Type'=> '07',
                    'Date'=> $order->preferred_pickup_date,
                    'Time'=> $order->preferred_pickup_time,
                ],
                'Package' => $order->cart->items->map(fn($item) => [
                    'PackagingType'=> ['Code'=>'02'],
                    'Dimensions'=> [
                        'DimensionsType'=>'CM',
                        'Length'=> $item->quote->service->pricingRule->max_length,
                        'Width'=> $item->quote->service->pricingRule->max_width,
                        'Height'=> $item->quote->service->pricingRule->max_height,
                    ],
                    'PackageWeight'=> [
                        'UnitOfMeasurement'=> ['Code'=>'KGS'],
                        'Weight'=> $item->quote->price,
                    ],
                ])->toArray(),
            ],
        ];

        try {
            $resp = $this->http->post($url, [
                'auth'    => [$carrier->api_key, $carrier->api_secret],
                'json'    => $payload,
            ]);
            $data = json_decode($resp->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            Log::error("UPS shipment error: {$e->getMessage()}");
            return;
        }

        OrderCarrierResponse::create([
            'order_id'   => $order->id,
            'carrier_id' => $carrier->id,
            'payload'    => $data,
        ]);

        Label::create([
            'order_id'           => $order->id,
            'external_label_url' => $data['ShipmentResponse']['LabelResults'][0]['ShippingLabel'][0]['GraphicImage'] ?? null,
        ]);
    }
}