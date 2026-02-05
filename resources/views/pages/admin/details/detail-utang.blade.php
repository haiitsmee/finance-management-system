@extends("components.layout")
@section('title', 'Detail Utang')   

@section('content')
<h2 class="text-2xl font-bold mb-4">Detail Utang</h2>

<div class="flex flex-row px-4 py-5 justify-start max-w-screen">
    <div class="flex flex-col min-w-1/3 gap-4">
        <div class="flex-col">
            <p>Kategori Utang</p>
            <p class="text-gray-900 text-lg font-bold py-2"> {{ $utangData['category'] }}</p>
        </div>
        <div class="flex-col">
            <p>Pemasok</p>
            <p class="text-gray-900 text-lg font-bold py-2"> {{ $utangData['supplier'] }}</p>
        </div>
        <div class="flex-col">
            <p>Jumlah Utang</p>
            <p class="text-gray-900 text-lg font-bold py-2"> {{ formatCurrency(amount: $utangData['amount']) }}</p>
        </div>
        <div class="flex-col">
            <p>Divisi Terkait</p>
            <p class="text-gray-900 text-lg font-bold py-2"> {{ $utangData['business_name'] ?? $business }}</p>
        </div>  
        <div class="flex-col">
            <p>Deskripsi</p>
            <p class="text-gray-900 text-lg font-medium py-2"> {{ $utangData['description'] }}</p>
        </div>  
    </div>

    <div class="flex flex-col min-w-1/3 gap-4">
        <div class="flex-col">
            <p>Tanggal Transaksi</p>
            <p class="text-gray-900 text-lg font-bold py-2"> {{ $utangData['date'] }}</p>
        </div>
        <div class="flex-col">
            <p>Jatuh Tempo</p>
            <p class="text-gray-900 text-lg font-bold py-2"> {{ $utangData['due_date'] }}</p>
        </div>
        <div class="flex-col">
            <p>Status</p>
            @if ($utangData['is_paid'])
                <p class="text-gray-900 text-lg font-bold py-2">Sudah Dibayar</p>
            @else
                <p class="text-gray-900 text-lg font-bold py-2">Belum Dibayar</p>
            @endif
        </div>  
    </div>
</div>

<div class="flex flex-col gap-2">
    <span class="mt-4">Bukti Utang</span>
    @if ($utangData->document_path)
        <div class="ml-4 w-1/3 h-auto border border-black flex items-center justify-center">
            <img src="{{ asset('storage/' . $utangData['document_path']) }}" alt="Bukti Pembayaran">
        </div>
    @else
        <div class="ml-4 w-auto h-48 border border-black flex items-center justify-center">
            <span>Tidak ada gambar bukti Utang!</span>
        </div>
    @endif
    <form id="settleForm" action="{{ route(auth()->user()->role . '.utangpiutang.settle', ['businesses' => session('current_business'), 'id' => $utangData['id']]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="flex flex-col gap-2 mt-4">
        <label class="font-medium">Bukti Pelunasan</label>

        @if ($utangData['is_paid'])
            @if ($utangData->repayment_document_path)
                <div class="ml-4 w-1/3 h-auto border border-black flex items-center justify-center">
                    <img src="{{ asset('storage/' . $utangData['repayment_document_path']) }}" alt="Bukti Pelunasan">
                </div>
            @else
                <input 
                    type="file" 
                    id="repayment_document"
                    name="repayment_document" 
                    accept=".pdf,.jpg,.png,.docx" 
                    class="border p-2 rounded-md"
                    required
                /> 
            @endif
        @else
            <input 
                type="file" 
                id="repayment_document"
                name="repayment_document" 
                accept=".pdf,.jpg,.png,.docx" 
                class="border p-2 rounded-md"
                required
            /> 

        @endif
    </div>
    
        <div class="flex justify-end mt-4">
            <button id="settleButton" type="submit" class="flex mt-7 mx-4 h-10 px-4 py-2 bg-blue-900 border-1 border-white text-white rounded-md hover:bg-gray-200 hover:text-blue-900 hover:border-blue-900" disabled>Pelunasan</button>
            <a href="{{ route(auth()->user()->role . '.utangpiutang.cetak-pdf', ['businesses' => session('current_business'), 'id' => $utangData['id']]) }}"" type="button" class="flex w-max h-10 p-4 mt-7 items-center rounded-md border bg-[#050A30] text-white text-md self-end hover:bg-gray-200 hover:text-[#050A30]">
                <span>Cetak PDF</span>
            </a>
        </div>
    </form>

</div>

<script>
    const fileInput = document.getElementById('repayment_document');
    const settleButton = document.getElementById('settleButton');

    fileInput.addEventListener('change', function() {
        settleButton.disabled = !fileInput.value;
    });
</script>

@endsection
