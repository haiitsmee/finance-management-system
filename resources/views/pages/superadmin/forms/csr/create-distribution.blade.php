@extends("components.layout")
@section('title', 'Tambah Penyaluran')

@section('content')

<form action="{{ route('superadmin.csr.distribution.add') }}" method="POST" enctype="multipart/form-data" class="w-full">
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
                name="activity" 
                label="Nama Kegiatan"
                :placeholder="'Masukan Nama Kegiatan'"
                type="text"
            />
            <x-custom-input 
                name="purpose" 
                label="Tujuan Donasi"
                :placeholder="'Masukan Tujuan Donasi'"
                type="text"
            />
        </div>
        <div class="flex flex-col">
            <x-custom-input 
                name="amount" 
                label="Jumlah Dana"
                :placeholder="'Masukan jumlah Dana'"
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
                placeholder="ex. Donasi untuk Kegiatan Sosial"
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

@if ($errors->has('amount'))
    <div class="text-red-500 text-sm mt-2">
        {{ $errors->first('amount') }}
    </div>
@endif

@endsection
