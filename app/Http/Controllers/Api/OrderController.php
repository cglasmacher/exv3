<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Jobs\ProcessOrderJob;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->authorizeResource(Order::class, 'order');
    }

    public function index(): JsonResponse
    {
        $orders = Order::with('cart.items.quote.service', 'labels', 'payments')
                       ->where('user_id', auth()->id())
                       ->paginate(10);
        return response()->json($orders);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $data = $request->validated();

        $order = Order::create([
            'cart_id'                => $data['quote_id'],
            'user_id'                => auth()->id(),
            'billing_address_id'     => $data['billing_address_id'] ?? null,
            'payment_provider'       => $data['payment_provider'],
            'user_payment_method_id' => $data['payment_method_id'] ?? null,
            'total_price'            => 0,
            'status'                 => 'pending',
        ]);

        // Dispatch job to process payment and shipments
        ProcessOrderJob::dispatch($order->id);

        return response()->json($order->load('labels', 'payments'), 202);
    }

    public function show(Order $order): JsonResponse
    {
        return response()->json($order->load('cart.items.quote.service', 'labels', 'payments'));
    }

    public function update(StoreOrderRequest $request, Order $order): JsonResponse
    {
        $order->update($request->validated());
        return response()->json($order);
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();
        return response()->json(null, 204);
    }
}