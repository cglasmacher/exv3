<?php
namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function store(Request $request, Cart $cart)
    {
        $data = $request->validate([
            'quote_id'  => 'required|exists:quotes,id',
            'quantity'  => 'required|integer|min:1',
        ]);

        $quote = Quote::findOrFail($data['quote_id']);

        $cartItem = CartItem::create([
            'cart_id'    => $cart->id,
            'quote_id'   => $quote->id,
            'quantity'   => $data['quantity'],
            'price_net'  => $quote->vk_net,
            'price_gross'=> $quote->vk_gross,
        ]);

        return response()->json($cartItem, 201);
    }
}