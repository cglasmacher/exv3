<?php
namespace App\Services\Carriers;

use App\Models\Carrier;
use App\Models\QuoteRequest;
use App\Models\Quote;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FedexQuoteProvider implements CarrierQuoteProviderInterface
{
    protected Client $http;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 15]);
    }

    public function generateQuotes(Carrier $carrier, QuoteRequest $request): void
    {
        $url = $carrier->endpoints['rates'] ?? env('FEDEX_RATE_URL');
        $payload = [
            'accountNumber'=> $carrier->api_key,
            'origin'=>[
                'postalCode'=> $request->pickupAddress->postcode,
                'countryCode'=> $request->pickupAddress->country,
            ],
            'destination'=>[
                'postalCode'=> $request->recipient_postcode,
                'countryCode'=> $request->recipient_country,
            ],
            'pickup'=>[
                'date'=> $request->preferred_pickup_date,
                'time'=> $request->preferred_pickup_time,
            ],
            'packages'=> $request->items->map(fn($i)=>[
                'weight'=> $i->weight,
                'dimensions'=>[
                    'length'=> $i->length,
                    'width'=> $i->width,
                    'height'=> $i->height,
                ],
            ])->toArray(),
        ];
        try {
            $resp = $this->http->post($url, [
                'headers'=>['Content-Type'=>'application/json'],
                'auth'=>[$carrier->api_key,$carrier->api_secret],
                'json'=> $payload,
            ]);
            $data = json_decode($resp->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            Log::error("FedEx quote error: {\$e->getMessage()}");
            return;
        }
        foreach ($data['rateReplyDetails'] ?? [] as $detail) {
            $service = $carrier->services->firstWhere('service_code', $detail['serviceType']);
            if (!$service) continue;
            $rateDetail = $detail['ratedShipmentDetails'][0]['shipmentRateDetail']['totalNetCharge'];
            Quote::create([
                'quote_request_id'=> $request->id,
                'service_id'=> $service->id,
                'price'=> $rateDetail['amount'],
                'currency'=> $rateDetail['currency'],
                'delivery_time_days'=> $detail['transitTime'] ?? $service->delivery_days,
                'expires_at'=> now()->addMinutes(30),
            ]);
        }
    }
}