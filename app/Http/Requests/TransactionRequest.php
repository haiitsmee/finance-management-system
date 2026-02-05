<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'business_id' => 'required',
            'transaction_category_id' => 'required',
            'product' => 'required|string',
            'price' => 'required|numeric',
            'product_quantity' => 'required|numeric',
            'total' => 'required|numeric',
            'note' => 'required|string',
            'transaction_date' => 'required|date',
            'description' => 'required|string',
            'status' => 'required|string',
            'image' => 'required|string'
        ];
    }
}
