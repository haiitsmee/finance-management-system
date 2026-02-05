<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionIncomeRequest extends FormRequest
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

    public function messages(): array
    {
        return [
            'transaction_category_id.required' => 'Kategori transaksi wajib dipilih.',
            'transaction_category_id.numeric' => 'Kategori transaksi harus berupa angka.',

            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi.',
            'tanggal_transaksi.date' => 'Tanggal transaksi harus berupa format tanggal yang valid.',

            'produk.required' => 'Nama produk wajib diisi.',
            'produk.string' => 'Nama produk harus berupa teks.',

            'jumlah_unit.required' => 'Jumlah unit wajib diisi.',
            'jumlah_unit.numeric' => 'Jumlah unit harus berupa angka.',

            'total.required' => 'Total wajib diisi.',
            'total.string' => 'Total harus berupa teks.',

            'harga.required' => 'Harga wajib diisi.',
            'harga.string' => 'Harga harus berupa teks.',

            'status.required' => 'Status wajib diisi.',
            'status.string' => 'Status harus berupa teks.',

            'deskripsi.string' => 'Deskripsi harus berupa teks.',

            'gambar.mimes' => 'Gambar hanya boleh berformat JPG, JPEG, PNG, atau PDF.',
            'gambar.max' => 'Ukuran file gambar tidak boleh lebih dari 2 MB.'
        ];

    }
}
