<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quote_id'           => ['required', 'integer', 'exists:quotes,id'],
            'billing_address_id' => ['nullable', 'integer', 'exists:addresses,id'],
            'payment_provider'   => ['required', 'string', 'exists:payment_providers,slug'],
            'payment_method_id'  => ['nullable', 'integer', 'exists:user_payment_methods,id'],
        ];
    }
}