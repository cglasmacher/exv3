<?php
namespace App\Services\Carriers;

use App\Models\Carrier;
use App\Models\QuoteRequest;
use App\Models\Quote;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DhlExpressQuoteProvider implements CarrierQuoteProviderInterface
{
    protected Client $http;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 15]);
    }

    public function generateQuotes(Carrier $carrier, QuoteRequest $request): void
    {
        $url = $carrier->endpoints['rates'] ?? env('DHL_EXPRESS_RATE_URL');
        $rateRequest = [
            'pickup' => [
                'postalCode'  => $request->pickup_address->postcode,
                'cityName'    => $request->pickup_address->city,
                'countryCode' => $request->pickup_address->country,
                'date'        => $request->preferred_pickup_date,
                'time'        => $request->preferred_pickup_time,
            ],
            'delivery' => [
                'postalCode'  => $request->recipient_postcode,
                'cityName'    => $request->recipient_city,
                'countryCode' => $request->recipient_country,
            ],
            'unitOfMeasurement'  => 'SI',
            'isCustomerAgreement'=> false,
            'packages' => $request->items->map(fn($i) => [
                'weight'     => $i->weight,
                'dimensions' => [
                    'length' => $i->length,
                    'width'  => $i->width,
                    'height' => $i->height,
                ],
            ])->toArray(),
        ];
        try {
            $resp = $this->http->post($url, [
                'headers' => [
                    'DHL-API-Key' => $carrier->api_key,
                    'Content-Type'=> 'application/json',
                ],
                'json' => ['rateRequest' => $rateRequest],
            ]);
            $data = json_decode($resp->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            Log::error("DHL Express quote error: {$e->getMessage()}");
            return;
        }
        foreach ($data['products'] ?? [] as $prod) {
            $service = $carrier->services->firstWhere('service_code', $prod['productCode']);
            if (!$service) continue;
            Quote::create([
                'quote_request_id'   => $request->id,
                'service_id'         => $service->id,
                'price'              => $prod['totalPrice']['price'],
                'currency'           => $prod['totalPrice']['currency'],
                'delivery_time_days' => $prod['deliveryTime'],
                'expires_at'         => now()->addMinutes(30),
            ]);
        }
    }
}