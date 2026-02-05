@extends("components.layout-pdf")
@section('title', 'Detail Utang')

@section('content')
    <div class="flex flex-col p-10">
        <h2 class="text-2xl font-bold mb-4">Detail Utang</h2>

        <div class="flex gap-10 mt-7 items-center">
            <div class="flex flex-col min-w-1/3 gap-4">
                <div class="flex-col">
                    <p>Kategori Utang</p>
                    <p class="text-gray-900 text-lg font-bold py-2"> {{ $debt['category'] }}</p>
                </div>
                <div class="flex-col">
                    <p>Pemasok</p>
                    <p class="text-gray-900 text-lg font-bold py-2"> {{ $debt['supplier'] }}</p>
                </div>
                <div class="flex-col">
                    <p>Jumlah Utang</p>
                    <p class="text-gray-900 text-lg font-bold py-2"> {{ formatCurrency(amount: $debt['amount']) }}</p>
                </div>
                <div class="flex-col">
                    <p>Divisi Terkait</p>
                    <p class="text-gray-900 text-lg font-bold py-2"> {{ $debt->business->name }}</p>
                </div>
                <div class="flex-col">
                    <p>Deskripsi</p>
                    <p class="text-gray-900 text-lg font-medium py-2"> {{ $debt['description'] }}</p>
                </div>
            </div>

            <div class="flex flex-col min-w-1/3 gap-4">
                <div class="flex-col">
                    <p>Tanggal Transaksi</p>
                    <p class="text-gray-900 text-lg font-bold py-2"> {{ $debt['date'] }}</p>
                </div>
                <div class="flex-col">
                    <p>Jatuh Tempo</p>
                    <p class="text-gray-900 text-lg font-bold py-2"> {{ $debt['due_date'] }}</p>
                </div>
                <div class="flex-col">
                    <p>Status</p>
                    @if ($debt['is_paid'])
                        <p class="text-gray-900 text-lg font-bold py-2">Sudah Dibayar</p>
                    @else
                        <p class="text-gray-900 text-lg font-bold py-2">Belum Dibayar</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-20">
            <div class="flex flex-col gap-4">
                <span class="mt-4">Bukti Utang</span>
                @if ($debt->document_path)
                    <div class="ml-4 w-1/3 h-auto border border-black flex items-center justify-center">
                        <img src="{{ $imageDocument }}" alt="Bukti Pembayaran">
                    </div>
                @else
                    <div class="ml-4 w-auto h-48 border border-black flex items-center justify-center">
                        <span>Tidak ada gambar bukti Utang!</span>
                    </div>
                @endif
            </div>
            <div class="flex flex-col gap-4">
                <span class="mt-4">Bukti Pelunasan</span>
                @if ($debt->repayment_document_path)
                    <div class="w-1/3 h-auto border border-black flex items-center justify-center">
                        <img src="{{ $imageRepayment }}" alt="Bukti Pelunasan">
                    </div>
                @else
                    <div class="w-auto h-48 border border-black flex items-center justify-center">
                        <span>Tidak ada gambar bukti pelunasan!</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection