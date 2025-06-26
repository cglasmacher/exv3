<?php
namespace App\Services\Carriers;

use App\Models\Carrier;
use App\Models\QuoteRequest;
use App\Models\Quote;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DpdQuoteProvider implements CarrierQuoteProviderInterface
{
    protected Client $http;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 15]);
    }

    public function generateQuotes(Carrier $carrier, QuoteRequest $request): void
    {
        $url = $carrier->endpoints['rates'] ?? env('DPD_RATE_URL');
        $parcels = $request->items->map(fn($i)=>[
            'weight'=> $i->weight,
            'length'=> $i->length,
            'width'=> $i->width,
            'height'=> $i->height,
        ])->toArray();
        try {
            $resp = $this->http->post($url, [
                'headers'=>['Authorization'=>"Bearer {$carrier->api_key}"],
                'json'=>[
                    'origin'=> $request->pickup_address->postcode,
                    'destination'=> $request->recipient_postcode,
                    'pickupDate'=> $request->preferred_pickup_date,
                    'pickupTime'=> $request->preferred_pickup_time,
                    'parcels'=> $parcels,
                ],
            ]);
            $data = json_decode($resp->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            Log::error("DPD quote error: {\$e->getMessage()}");
            return;
        }
        foreach ($data['rates'] ?? [] as $r) {
            $service = $carrier->services->firstWhere('service_code', $r['code']);
            if (!$service) continue;
            Quote::create([
                'quote_request_id'=> $request->id,
                'service_id'=> $service->id,
                'price'=> $r['price'],
                'currency'=> $r['currency'] ?? 'EUR',
                'delivery_time_days'=> $r['deliveryTime'] ?? $service->delivery_days,
                'expires_at'=> now()->addMinutes(30),
            ]);
        }
    }
}