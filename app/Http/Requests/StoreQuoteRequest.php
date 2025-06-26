<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // optional: implement auth/guest logic
    }

    public function rules(): array
    {
        return [
            'sender_country'    => ['required', 'string', 'size:2'],
            'sender_postcode'   => ['required', 'string', 'max:10'],
            'sender_city'       => ['required', 'string', 'max:100'],
            'recipient_country' => ['required', 'string', 'size:2'],
            'recipient_postcode'=> ['required', 'string', 'max:10'],
            'recipient_city'    => ['required', 'string', 'max:100'],
            'items'             => ['required', 'array', 'min:1'],
            'items.*.item_type' => ['required', 'in:package,pallet,document'],
            'items.*.weight'    => ['required', 'numeric', 'min:0.1'],
            'items.*.length'    => ['nullable', 'numeric', 'min:0.1'],
            'items.*.width'     => ['nullable', 'numeric', 'min:0.1'],
            'items.*.height'    => ['nullable', 'numeric', 'min:0.1'],
            'items.*.quantity'  => ['required', 'integer', 'min:1'],
        ];
    }
    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'sender_postcode.regex'    => 'Die Absender-PLZ muss nach den länderspezifischen Gültigkeiten dargestellt sein.',
            'recipient_postcode.regex' => 'Die Empfänger-PLZ muss nach den länderspezifischen Gültigkeiten dargestellt sein.',
            'items.*.weight.min'       => 'Das Gewicht muss mindestens 0,1 kg betragen.',
            'items.*.length.min'       => 'Die Länge muss mindestens 1 cm betragen.',
            'items.*.width.min'        => 'Die Breite muss mindestens 1 cm betragen.',
            'items.*.height.min'       => 'Die Höhe muss mindestens 1 cm betragen.',
        ];
    }
}

