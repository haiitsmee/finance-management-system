<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionCategoryRequest extends FormRequest
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
            'judul_kategori' => 'required|string',
            'tipe_kategori' => 'required'
        ];
    }

    public function message() {
        return [
            'judul_kategori.required' => 'Judul kategori wajib diisi.',
            'judul_kategori.string' => 'Judul kategori wajib berupa gabungan teks dan angka.',
            'tipe_kategori.required' => 'Tipe kategori wajib dipilih.'
        ];
    }
}
