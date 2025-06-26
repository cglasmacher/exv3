<?php
namespace App\Services;

use App\Models\QuoteRequest;
use App\Models\Quote;
use App\Services\PricingService;
use Illuminate\Support\Facades\DB;

class QuoteService
{
    protected PricingService $pricing;

    public function __construct(PricingService $pricing)
    {
        $this->pricing = $pricing;
    }

    /**
     * Generates quotes for a request, applies pricing, and returns collection of quotes.
     */
    public function generateQuotes(QuoteRequest $request)
    {
        DB::transaction(function () use ($request) {
            // Delete old quotes
            $request->quotes()->delete();

            // Dispatch job or inline generate
            foreach ($request->items as $item) {
                foreach ($request->availableServices() as $service) {
                    $quote = Quote::create([
                        'quote_request_id'   => $request->id,
                        'service_id'         => $service->id,
                        'price'              => $this->calculateEk($request, $item, $service),
                        'currency'           => $service->pricingRule->currency ?? 'EUR',
                        'delivery_time_days' => $service->delivery_days,
                        'expires_at'         => now()->addMinutes(30),
                    ]);

                    // Apply markup & taxes
                    $this->pricing->apply($quote);
                }
            }
        });

        return $request->quotes()->with('service')->get();
    }

    /**
     * Calculate base EK price. Replace with carrier-specific API calls.
     */
    protected function calculateEk(QuoteRequest $request, $item, $service): float
    {
        // Placeholder: for now return service base + weight factor
        $base = $service->base_rate;
        $factor = $service->rate_per_kg * $item->weight;
        return round($base + $factor, 2);
    }
}