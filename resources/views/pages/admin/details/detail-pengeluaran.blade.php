@extends('components.layout')

@section('title', 'Detail Pengeluaran')

@section('content')
    <div class="flex flex-col">
        <h1 class="text-2xl text-black">Detail Pengeluaran Divisi: <strong>{{ session('current_business_name') }}</strong>
        </h1>

        <div class="flex gap-40 mt-7">
            <div class="flex flex-col gap-2">
                <span class="">Tanggal Transaksi</span>
                <span class="font-bold">{{ $transactionDateFormatted }}</span>
                <span class="mt-4">Kategori Pengeluaran</span>
                <span class="font-bold">{{ $transactionExpense->transactionCategory->title }}</span>
                <span class="mt-4">Nama Produk</span>
                <span class="font-bold">{{ $transactionExpense->product }}</span>
                <span class="mt-8">Deskripsi</span>
                <span class="font-bold">{{ $transactionExpense->description }}</span>
            </div>
            <div class="flex flex-col gap-2">
                <span class="">Jumlah Unit</span>
                <span class="font-bold">{{ $transactionExpense->product_quantity }}</span>
                <span class="mt-4">Harga per-unit</span>
                <span class="font-bold">@currency($transactionExpense->price)</span>
                <span class="mt-4">Total Pengeluaran</span>
                <span class="font-bold">@currency($transactionExpense->total)</span>
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <span class="mt-4">Bukti Pembayaran</span>
            @if ($fileExists)
                <div class="w-1/3 h-auto border border-black flex items-center justify-center">
                    <img src="{{ asset('storage/' . $transactionExpense->image) }}" alt="Bukti Pembayaran">
                </div>
            @else
                <div class="w-auto h-48 border border-black flex items-center justify-center">
                    <span>Tidak ada gambar bukti pembayaran!</span>
                </div>
            @endif
        </div>
        <div class="flex justify-end">
            <a href="{{ route(auth()->user()->role . '.catat-pengeluaran.cetak-pdf', ['businesses' => session('current_business'), 'id' => $transactionExpense->id]) }}" type="button" class="flex w-max h-10 p-4 mt-7 items-center rounded-md border bg-[#050A30] text-white text-md self-end hover:bg-gray-200 hover:text-[#050A30]"><span>Cetak PDF</span>
            </a>
        </div>
    </div>

@endsection