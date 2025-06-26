<?php
namespace App\Services;

use App\Models\PriceMarkup;
use App\Models\Quote;

class PricingService
{
    /**
     * Apply markup and VAT to a quote
     */
    public function apply(Quote $quote): Quote
    {
        // Einkaufspreis (Netto)
        $ekNet = $quote->price;
        // Einkaufspreis (Brutto), z.B. 19% MwSt
        $ekGross = round($ekNet * 1.19, 2);

        // Gesamtgewicht aller Items dieser Anfrage
        $weight = $quote->quoteRequest->items->sum('weight');

        // Finde passendste Markup-Regel
        $markup = PriceMarkup::where('service_id', $quote->service_id)
            ->where('weight_min','<=',$weight)
            ->where(function($q) use($weight) {
                $q->whereNull('weight_max')->orWhere('weight_max','>=',$weight);
            })
            ->orderBy('weight_min','desc')
            ->first();

        $percent = ($markup->markup_percent ?? 0) / 100;
        // Verkaufspreis Netto
        $vkNet = round($ekNet * (1 + $percent), 2);
        // Verkaufspreis Brutto
        $vkGross = round($vkNet * 1.19, 2);

        // Speichere in Quote
        $quote->update([
            'ek_net'   => $ekNet,
            'ek_gross' => $ekGross,
            'vk_net'   => $vkNet,
            'vk_gross' => $vkGross,
        ]);

        return $quote;
    }
}