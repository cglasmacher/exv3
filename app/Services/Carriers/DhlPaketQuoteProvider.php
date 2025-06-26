<?php
namespace App\Services\Carriers;

use App\Models\Carrier;
use App\Models\QuoteRequest;
use App\Models\Quote;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DhlPaketQuoteProvider implements CarrierQuoteProviderInterface
{
    protected Client $http;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 15]);
    }

    public function generateQuotes(Carrier $carrier, QuoteRequest $request): void
    {
        $url = $carrier->endpoints['rates'] ?? env('DHL_PAKET_RATE_URL');
        $parcels = $request->items->map(fn($i) => [
            'weightInKg' => $i->weight,
            'lengthInCm' => $i->length,
            'widthInCm'  => $i->width,
            'heightInCm' => $i->height,
        ])->toArray();
        try {
            $resp = $this->http->post($url, [
                'auth' => [$carrier->api_key, $carrier->api_secret],
                'json' => [
                    'fromPostalCode'=> $request->pickup_address->postcode,
                    'toPostalCode'  => $request->recipient_postcode,
                    'parcels'       => $parcels,
                ],
            ]);
            $data = json_decode($resp->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            Log::error("DHL Paket quote error: {$e->getMessage()}");
            return;
        }
        foreach ($data['products'] ?? [] as $prod) {
            $service = $carrier->services->firstWhere('service_code', $prod['productCode']);
            if (!$service) continue;
            Quote::create([
                'quote_request_id'   => $request->id,
                'service_id'         => $service->id,
                'price'              => $prod['price'],
                'currency'           => $prod['currency'] ?? 'EUR',
                'delivery_time_days' => $prod['deliveryTime'] ?? $service->delivery_days,
                'expires_at'         => now()->addMinutes(30),
            ]);
        }
    }
}