@extends("components.layout")
@section('title', 'Tambah Utang')

@section('content')

<form action="{{ route(auth()->user()->role . '.utangpiutang.store', ['businesses' => session('current_business')]) }}" method="POST" enctype="multipart/form-data" class="w-full">
    @csrf

    <div class="flex min-w-full justify-start gap-8 px-4 py-5 md:flex-row flex-col">

        <div class="flex flex-col min-w-1/3">

            <x-custom-input 
                name="date" 
                label="Tanggal Transaksi"
                placeholder="Tanggal"
                type="date"
            />

            <x-custom-input 
                name="category" 
                label="Kategori"
                placeholder="Kategori"
                type="option"
                :value="['Pembelian Barang','Lainnya']"
            />

            <x-custom-input 
                name="supplier" 
                label="Pemasok"
                placeholder="Instansi/Perorangan/dll."
                type="text"
            />  

            <x-custom-input 
                name="amount" 
                label="Jumlah"
                placeholder="Rp"
                type="text"
            /> 
        </div>

        <div class="flex flex-col min-w-1/3">
            <x-custom-input 
                name="business_name" 
                label="Divisi Terkait"
                :placeholder="session('current_business_name')"
                :value="session('current_business_name')"
                type="text"
                :editable="false"
            />

            <x-custom-input 
                name="due_date" 
                label="Jatuh Tempo"
                placeholder="Tanggal"
                type="date"
            />

            <x-custom-input 
                name="document_path" 
                label="Bukti Utang"
                type="document"
            />

            <x-custom-input 
                name="description" 
                label="Deskripsi"
                placeholder="ex. Utang diperlukan untuk pembelian barang A"
                type="description"
            />  
        </div>
    </div>

    <div class="flex px-4 w-1/2 gap-4">
        <div class="flex px-4 gap-4">
    <button type="submit" class="p-2 rounded-md w-35 mt-7 bg-[#050A30] hover:bg-gray-200 hover:text-[#050A30] text-white border border-[#050A30]">
        Simpan
    </button>
    <button type="button" class="cancel-button p-2 rounded-md w-35 mt-7 bg-[#F1F1F1] hover:bg-[#050A30] hover:text-gray-200 text-[#050A30] border border-[#050A30]" onclick="window.history.back();">
        Cancel
    </button>
</div>
    </div>
</form>

@endsection
