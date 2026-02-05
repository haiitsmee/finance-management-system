@extends("components.layout")
@section('title', 'Tambah CSR')

@section('content')

<form action="{{ route('superadmin.csr.create-income.submit') }}" method="POST" enctype="multipart/form-data" class="w-full">
    @csrf

    <div class="flex min-w-full justify-start gap-8 px-4 py-5 md:flex-row flex-col">
        <div class="flex flex-col min-w-1/3">
            <x-custom-input 
                name="date" 
                label="Tanggal"
                :placeholder="'Tanggal Pengumpulan Dana'"
                type="date"
            />
            <x-custom-input 
                name="source" 
                label="Sumber Dana"
                :placeholder="'Sumber uang'"
                type="text"
            />

            <x-custom-input 
                name="amount" 
                label="Jumlah Dana"
                :placeholder="'Masukan jumlah uang'"
                type="harga"
            />

            <x-custom-input 
                name="document" 
                label="Bukti Amal"
                type="document"
            />

            <x-custom-input 
                name="description" 
                label="Deskripsi"
                placeholder="ex. Donasi dari PT. Sejahtera Abadi"
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
