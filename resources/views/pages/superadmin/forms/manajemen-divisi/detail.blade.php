@extends('components.layout')

@section('title', 'Detail Divisi')

@section('content')
    <div class="flex flex-col">
        <h1 class="text-3xl text-black">Manajemen Divisi</h1>

        <div class="flex flex-col gap-2 mt-10">
            <div class="flex p-2 justify-between border-b-2 border-b-gray-400 font-bold text-gray-400 text-md">
                <span>ID Divisi</span>
                <span>1</span>
            </div>
            <div class="flex p-2 justify-between border-b-2 border-b-gray-400 font-bold text-md">
                <span>Nama Divisi</span>
                <span>{{ $business->name }}</span>
            </div>

            <div class="grid grid-cols-4 mt-5">
                <div class="flex flex-col gap-2">
                    <span>Penaggung Jawab</span>
                    <span class="ml-3 font-bold">{{ $business->responsible }}</span>
                </div>
                <div class="flex flex-col gap-2">
                    <span>Kontak Penaggung Jawab</span>
                    <span class="ml-3 font-bold">{{ $business->contact_responsible }}</span>
                </div>
                <div class="flex flex-col gap-2">
                    <span>Tanggal Ditambahkan</span>
                    <span class="ml-3 font-bold">{{ $transactionDateFormatted }}</span>
                </div>
                <div class="flex flex-col gap-2">
                    <span>Jumlah Karyawan</span>
                    <span class="ml-3 font-bold">{{ $business->employee_headcount }}</span>
                </div>
            </div>
            <div class="flex flex-col mt-3 gap-2">
                <span>Deskripsi</span>
                <span class="ml-3">{{ $business->description }}</span>
            </div>
            <div class="flex justify-end mt-3">
            <a href="{{ route('superadmin.manajemen-divisi.edit', ['manajemen_divisi' => $business->id]) }}" type="button" class="flex w-max h-10 p-4 mt-7 items-center rounded-md border bg-[#050A30] text-white text-md self-end hover:bg-gray-200 hover:text-[#050A30]"><span>Edit</span>
            </a>
        </div>
        </div>
    </div>
@endsection