<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuoteRequest;
use App\Models\QuoteRequest;
use App\Jobs\ProcessQuoteJob;
use Illuminate\Http\JsonResponse;

class QuoteRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['store']);
        $this->authorizeResource(QuoteRequest::class, 'quoteRequest');
    }

    public function index(): JsonResponse
    {
        $requests = QuoteRequest::with('items', 'quotes.service')->paginate(10);
        return response()->json($requests);
    }

    public function store(StoreQuoteRequest $request): JsonResponse
    {
        $data = $request->validated();

        $quoteRequest = QuoteRequest::create([
            'user_id'            => auth()->id(),
            'guest_token'        => auth()->check() ? null : ($data['guest_token'] ?? null),
            'sender_country'     => $data['sender_country'],
            'sender_postcode'    => $data['sender_postcode'],
            'sender_city'        => $data['sender_city'],
            'recipient_country'  => $data['recipient_country'],
            'recipient_postcode' => $data['recipient_postcode'],
            'recipient_city'     => $data['recipient_city'],
        ]);

        foreach ($data['items'] as $item) {
            $quoteRequest->items()->create($item);
        }

        // Dispatch job to process quotes asynchronously
        ProcessQuoteJob::dispatch($quoteRequest->id);

        return response()->json($quoteRequest->load('items'), 202);
    }

    public function show(QuoteRequest $quoteRequest): JsonResponse
    {
        return response()->json($quoteRequest->load('items', 'quotes.service'));
    }

    public function update(StoreQuoteRequest $request, QuoteRequest $quoteRequest): JsonResponse
    {
        $quoteRequest->update($request->validated());

        // Redispatch job after update
        ProcessQuoteJob::dispatch($quoteRequest->id);

        return response()->json($quoteRequest->load('items', 'quotes.service'));
    }

    public function destroy(QuoteRequest $quoteRequest): JsonResponse
    {
        $quoteRequest->delete();
        return response()->json(null, 204);
    }
}