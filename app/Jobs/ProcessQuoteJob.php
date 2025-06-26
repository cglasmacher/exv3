<?php
namespace App\Jobs;
use App\Models\QuoteRequest;
use App\Services\QuoteService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessQuoteJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected int $quoteRequestId;

    public function __construct(int $quoteRequestId)
    {
        $this->quoteRequestId = $quoteRequestId;
    }

    public function handle(QuoteService $quoteService): void
    {
        $quoteRequest = QuoteRequest::with('items')->findOrFail($this->quoteRequestId);
        $quoteService->generateQuotes($quoteRequest);
        event(new \App\Events\QuoteRequestCreated($quoteRequest));
    }
}