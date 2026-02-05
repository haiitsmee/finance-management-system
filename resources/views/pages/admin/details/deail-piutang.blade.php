@extends("components.layout")
@section('title', 'Detail Utang')   

@section('content')
<h2 class="text-2xl font-bold mb-4">Detail Utang</h2>
    <div class="flex flex-row px-4 py-5 justify-start max-w-screen">
        <div class="flex flex-col min-w-1/3 gap-4">
            <div class="flex-col">
                <p>Kategori Piutang</p>
                <p class="text-gray-900 text-lg font-bold px-5 py-2"> {{ $utangData['category'] }}</p>
            </div>
            <div class="flex-col">
                <p>Nomor Transaksi</p>
                <p class="text-gray-900 text-lg font-bold px-5 py-2"> {{ $utangData['supplier'] }}</p>
            </div>
            <div class="flex-col">
                <p>Jumlah Utang    </p>
                <p class="text-gray-900 text-lg font-bold px-5 py-2"> {{ formatCurrency(amount: $utangData['amount']) }}</p>
            </div>
            <div class="flex-col">
                <p>Divisi Terkait</p>
                <p class="text-gray-900 text-lg font-bold px-5 py-2"> {{ $business }}</p>
            </div>  
            <div class="flex-col">
                <p>Deskripsi</p>
                <p class="text-gray-900 text-lg font-medium px-5 py-2"> {{ $utangData['description'] }}</p>
            </div>  
        </div>
        <div class="flex flex-col min-w-1/3 gap-4">
            <div class="flex-col">
                <p>Tanggal Transaksi</p>
                <p class="text-gray-900 text-lg font-bold px-5 py-2"> {{ $utangData['date'] }}</p>
            </div>
            <div class="flex-col">
                <p>Jatuh Tempo</p>
                <p class="text-gray-900 text-lg font-bold px-5 py-2"> {{ $utangData['due_date'] }}</p>
            </div>
            <div class="flex-col">
                <p>Status</p>
                @if ( $utangData['is_paid'] )
                <p class="text-gray-900 text-lg font-bold px-5 py-2">Sudah Dibayar</p>
                @else
                <p class="text-gray-900 text-lg font-bold px-5 py-2">Belum Dibayar</p>
                @endif
            </div>  
        </div>
    </div>
    <div class="flex flex-col gap-2">
        <span class="mt-4">Bukti Pembayaran</span>
        @if ($utangData->document_path)
            <div class="ml-4 w-1/3 h-auto border border-black flex items-center justify-center">
                <img src="#" alt="Bukti Pembayaran">
            </div>
        @else
            <div class="ml-4 w-auto h-48 border border-black flex items-center justify-center">
                <span>Tidak ada gambar bukti pembayaran!</span>
            </div>
        @endif
    </div>
     <div class="flex justify-end">
            <a href="#" type="button" class="flex w-max h-10 p-4 mt-7 items-center rounded-md border bg-[#050A30] text-white text-md self-end hover:bg-gray-200 hover:text-[#050A30]"><span>Cetak PDF</span>
            </a>
        </div>
    </div>
@endsection