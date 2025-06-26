<?php
namespace App\Services\Carriers;

use App\Models\Carrier;
use App\Models\QuoteRequest;

interface CarrierQuoteProviderInterface
{
    /**
     * Generate quotes for a given carrier and request
     * Should create Quote models via Quote::create
     *
     * @param Carrier $carrier
     * @param QuoteRequest $request
     * @return void
     */
    public function generateQuotes(Carrier $carrier, QuoteRequest $request): void;
}