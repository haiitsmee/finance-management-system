<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessRequest extends FormRequest
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
            'nama' => ['required', 'string', 'max:255', 'regex:/^[A-Z][A-Za-z0-9\s]*$/'],
            'penanggung_jawab' => ['required', 'string', 'max:255'],
            'kontak' => ['required', 'string', 'max:255'],
            'jumlah_karyawan' => ['required', 'integer', 'min:1'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama divisi wajib diisi.',
            'nama.regex' => 'Nama divisi harus diawali huruf besar.',
            'nama.max' => 'Nama divisi maksimal 255 karakter.',

            'penanggung_jawab.required' => 'Penanggung jawab wajib diisi.',
            'kontak.required' => 'Kontak wajib diisi.',

            'jumlah_karyawan.required' => 'Jumlah karyawan wajib diisi.',
            'jumlah_karyawan.integer' => 'Jumlah karyawan harus berupa angka.',
            'jumlah_karyawan.min' => 'Jumlah karyawan minimal 1 orang.',
        ];
    }
}
