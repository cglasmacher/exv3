<?php
namespace App\Services\Carriers;

use App\Models\Carrier;
use App\Models\QuoteRequest;
use App\Models\Quote;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class UpsQuoteProvider implements CarrierQuoteProviderInterface
{
    protected Client $http;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 15]);
    }

    public function generateQuotes(Carrier $carrier, QuoteRequest $request): void
    {
        $url = $carrier->endpoints['rates'] ?? env('UPS_RATE_URL');
        $payload = [
            'Shipper'=>['Address'=>[
                'PostalCode'=> $request->pickupAddress->postcode,
                'CountryCode'=> $request->pickupAddress->country,
            ]],
            'ShipTo' =>['Address'=>[
                'PostalCode'=> $request->recipient_postcode,
                'CountryCode'=> $request->recipient_country,
            ]],
            'Pickup' =>[
                'PickupDateInfo'=>[
                    'Type'=>'07',
                    'Date'=> $request->preferred_pickup_date,
                    'Time'=> $request->preferred_pickup_time,
                ],
                'Address'=>[
                    'PostalCode'=> $request->pickupAddress->postcode,
                    'CountryCode'=> $request->pickupAddress->country,
                ],
            ],
            'Package'=> $request->items->map(fn($i)=>[
                'PackagingType'=>['Code'=>'02'],
                'Dimensions'=>['DimensionsType'=>'CM','Length'=> $i->length,'Width'=> $i->width,'Height'=> $i->height],
                'PackageWeight'=>['UnitOfMeasurement'=>['Code'=>'KGS'],'Weight'=> $i->weight],
            ])->toArray(),
        ];
        try {
            $resp = $this->http->post($url, ['auth'=>[$carrier->api_key,$carrier->api_secret],'json'=>$payload]);
            $data = json_decode($resp->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            Log::error("UPS quote error: {\$e->getMessage()}");
            return;
        }
        foreach ($data['RateResponse']['RatedShipment'] ?? [] as $rate) {
            $service = $carrier->services->firstWhere('service_code', $rate['Service']['Code']);
            if (!$service) continue;
            Quote::create([
                'quote_request_id'=> $request->id,
                'service_id'=> $service->id,
                'price'=> $rate['TotalCharges']['MonetaryValue'],
                'currency'=> $rate['TotalCharges']['CurrencyCode'],
                'delivery_time_days'=> $rate['GuaranteedDelivery']['BusinessDaysInTransit'] ?? $service->delivery_days,
                'expires_at'=> now()->addMinutes(30),
            ]);
        }
    }
}