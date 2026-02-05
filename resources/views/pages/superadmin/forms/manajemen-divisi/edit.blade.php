@extends('components.layout')

@section('title', 'Edit Admin')

@section('content')
    <div class="flex flex-col">
        <h1 class="text-3xl text-black">Edit Divisi</h1>

        @if ($errors->any())
        <div class="flex p-4 mt-3 text-sm text-red-800 rounded-lg bg-red-50"
        role="alert">
                <x-feathericon-info class="w-5 h-5" />
                <span class="sr-only">Kesalahan Inputan</span>
                <div>
                    <span class="font-medium ml-3">Pastikan sesuai dengan di bawah ini:</span>
                    <ul class="mt-1.5 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('superadmin.manajemen-divisi.update', ['manajemen_divisi' => $business->id]) }}" method="POST" class="mt-10">
            @csrf
            @method('PUT')
            <div class="flex gap-10">
                <div class="flex flex-col gap-5">
                    <x-form.input for="nama" type="text" label="Nama Divisi" placeholder="Nama divisi" :readonly="false"
                        :required="true" :value="$business->name" width="w-70" />
                    <x-form.input for="penanggung_jawab" type="text" label="Penanggung Jawab" placeholder="Penanggung Jawab" :readonly="false"
                        :required="true" :value="$business->responsible" width="w-70" />
                    <x-form.input for="kontak" type="text" label="Kontak"
                        placeholder="Kontak" :readonly="false" :required="true" :value="$business->contact_responsible" width="w-70" />
                    <x-form.input for="jumlah_karyawan" type="number" label="Jumlah Karyawan"
                        placeholder="Jumlah karyawan" :readonly="false" :required="true" :value="$business->employee_headcount" width="w-70" />
                    <div class="flex gap-2">
                        <x-forms.button type="submit" value="Edit"
                            class="w-35 mt-7 bg-[#050A30] hover:bg-gray-200 hover:text-[#050A30] text-white border border-[#050A30]" />
                        <x-forms.button type="button" value="Cancel"
                            onclick="window.location.href='{{ route('superadmin.manajemen-divisi.index') }}'"
                            class="cancel-button w-35 mt-7 bg-[#F1F1F1] hover:bg-[#050A30] hover:text-gray-200 text-[#050A30] border border-[#050A30]" />
                    </div>
                </div>
                <div>
                     <x-forms.textarea for="deskripsi" label="Deskripsi" rows="4" cols="30"
                            placeholder="Deskripsi" :value="$business->description">{{ $business->description }}</x-forms.textarea>
                </div>
            </div>
        </form>
    </div>
@endsection