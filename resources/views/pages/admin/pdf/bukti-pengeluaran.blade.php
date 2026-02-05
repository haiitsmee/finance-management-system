    @extends('components.layout-pdf')

    @section('title', 'Bukti Pemasukan')

    @section('content')
        <div class="flex flex-col p-10">
            <h1 class="text-2xl text-black">Bukti Pemasukan Divisi: <strong>{{ session('current_business_name') }}</strong>
            </h1>

            <div class="flex gap-40 mt-7 items-center">
                <div class="flex flex-col gap-2">
                    <span class="">Tanggal Transaksi</span>
                    <span class="font-bold">{{ $transactionDateFormatted }}</span>
                    <span class="mt-4">Kategori Pemasukan</span>
                    <span class="font-bold">{{ $transactionIncome->transactionCategory->title }}</span>
                    <span class="mt-4">Nama Produk</span>
                    <span class="font-bold">{{ $transactionIncome->product }}</span>
                    <span class="mt-8">Deskripsi</span>
                    <span class="font-bold">{{ $transactionIncome->description }}</span>
                </div>
                <div class="flex flex-col gap-2">
                    <span class="">Jumlah Unit</span>
                    <span class="font-bold">{{ $transactionIncome->product_quantity }}</span>
                    <span class="mt-4">Harga per-unit</span>
                    <span class="font-bold">@currency($transactionIncome->price)</span>
                    <span class="mt-4">Total Pemasukan</span>
                    <span class="font-bold">@currency($transactionIncome->total)</span>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <span class="mt-4">Bukti Pembayaran</span>
                @if ($imageData)
                    <div class="w-1/3 h-auto border border-black flex items-center justify-center">
                        <img src="{{ $imageData }}" alt="Bukti Pembayaran">
                    </div>
                @else
                    <div class="w-auto h-48 border border-black flex items-center justify-center">
                        <span>Tidak ada gambar bukti pembayaran!</span>
                    </div>
                @endif
            </div>
        </div>
    @endsection