<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Models\Address;
use Illuminate\Http\JsonResponse;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->authorizeResource(Address::class, 'address');
    }

    public function index(): JsonResponse
    {
        return response()->json(auth()->user()->addresses);
    }

    public function store(StoreAddressRequest $request): JsonResponse
    {
        $address = auth()->user()->addresses()->create($request->validated());
        return response()->json($address, 201);
    }

    public function show(Address $address): JsonResponse
    {
        return response()->json($address);
    }

    public function update(StoreAddressRequest $request, Address $address): JsonResponse
    {
        $address->update($request->validated());
        return response()->json($address);
    }

    public function destroy(Address $address): JsonResponse
    {
        $address->delete();
        return response()->json(null, 204);
    }
}