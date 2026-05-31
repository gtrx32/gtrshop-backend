<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],

            'delivery' => ['required', 'array'],
            'delivery.recipient_name' => ['required', 'string', 'max:255'],
            'delivery.phone' => ['required', 'string', 'max:50'],
            'delivery.email' => ['required', 'email', 'max:255'],
            'delivery.address' => ['required', 'string', 'max:1000'],

            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
