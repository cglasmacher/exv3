<?php
namespace App\Events;

use App\Models\QuoteRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuoteRequestCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public QuoteRequest $quoteRequest;

    public function __construct(QuoteRequest $quoteRequest)
    {
        $this->quoteRequest = $quoteRequest;
    }
}