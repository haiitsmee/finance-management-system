<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionExpenseRequest extends FormRequest
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
            'transaction_category_id' => 'required|numeric',
            'tanggal_transaksi' => 'required|date',
            'produk' => 'required|string',
            'jumlah_unit' => 'required|numeric',
            'total' => 'required|string',
            'harga' => 'required|string',
            'status' => 'required|string',
            'deskripsi' => 'nullable|string',
            'gambar' => 'mimes:jpg,jpeg,png,pdf|max:2048'
        ];
    }
}
