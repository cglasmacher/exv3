<?php
namespace App\Services\Carriers;

use App\Models\Carrier;
use App\Models\QuoteRequest;
use App\Models\Quote;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GenericQuoteProvider implements CarrierQuoteProviderInterface
{
    protected Client $http;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 10]);
    }

    public function generateQuotes(Carrier $carrier, QuoteRequest $request): void
    {
        $url = $carrier->endpoints['rates'] ?? null;
        if (!$url) {
            Log::warning("No rate endpoint for carrier {$carrier->slug}");
            return;
        }

        $payload = [
            'sender'    => [
                'country'  => $request->sender_country,
                'postcode' => $request->sender_postcode,
                'city'     => $request->sender_city,
            ],
            'recipient' => [
                'country'  => $request->recipient_country,
                'postcode' => $request->recipient_postcode,
                'city'     => $request->recipient_city,
            ],
            'pickup'    => [
                'addressId' => $request->pickup_address_id,
                'date'      => $request->preferred_pickup_date,
                'time'      => $request->preferred_pickup_time,
            ],
            'parcels'   => $request->items->map(fn($item) => [
                'type'      => $item->item_type,
                'weight'    => $item->weight,
                'length'    => $item->length,
                'width'     => $item->width,
                'height'    => $item->height,
                'quantity'  => $item->quantity,
            ])->toArray(),
        ];

        try {
            $response = $this->http->post($url, [
                'json' => array_merge($payload, ['api_key' => $carrier->api_key]),
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            Log::error("Generic provider error for {$carrier->slug}: {$e->getMessage()}");
            return;
        }

        foreach ($data['quotes'] ?? [] as $q) {
            $service = $carrier->services->firstWhere('service_code', $q['service_code']);
            if (!$service) continue;
            Quote::create([
                'quote_request_id'   => $request->id,
                'service_id'         => $service->id,
                'price'              => $q['price'],
                'currency'           => $q['currency'] ?? 'EUR',
                'delivery_time_days' => $q['delivery_days'] ?? $service->delivery_days,
                'expires_at'         => now()->addMinutes($q['validity'] ?? 30),
            ]);
        }
    }
}