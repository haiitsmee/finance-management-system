@extends("components.layout")
@section('title', 'Edit Utang')

@section('content')

@php
$debtCategory = [
    'Pembelian Barang' => 'Pembelian Barang',
    'Lainnya' => 'Lainnya',
];
@endphp

<form action="{{ route('admin.utangpiutang.update', ['businesses' => session('current_business'), 'id' => $debt->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="flex min-w-full justify-start gap-8 px-4 py-5 md:flex-row flex-col">
        <div class="flex flex-col min-w-1/3 ">
            <x-custom-input 
                name="date" 
                label="Tanggal Transaksi"
                placeholder="Tanggal"
                type="date"
                :value="old('date', $debt->date ?? now()->format('Y-m-d'))"
            />
            <x-custom-input 
                name="category" 
                label="Kategori Utang"
                placeholder="Kategori Utang"
                type="option"
                :value="['Pembelian Barang','Lainnya']"
                :selected="old('category', $debt->category ?? null)"
            />
            <x-custom-input 
                name="supplier" 
                label="Pemasok"
                placeholder="Instansi/Perorangan/dll."
                type="text"
                :value="old('supplier', $debt->supplier ?? null)"
            />  
            <x-custom-input 
                name="amount" 
                label="Jumlah Utang"
                placeholder="Rp"
                type="text"
                :value="old('amount', $debt->amount ?? null)"
            /> 
        </div>
        <div class="flex flex-col min-w-1/3">
            <x-custom-input 
                name="bussiness_name" 
                label="Divisi Terkait"
                :placeholder="session('current_business_name')"
                type="text"
                :editable="false"
                :value="old('bussiness_name', session('current_business_name'))"
            />
            <x-custom-input 
                name="due_date" 
                label="Jatuh Tempo"
                placeholder="Tanggal"
                type="date"
                :value="old('due_date', $debt->due_date ?? null)"
            />
            <x-custom-input 
                name="document_path" 
                label="Bukti Utang"
                placeholder="Tanggal"
                type="document"
                :value="old('document_path', $debt->document_path ?? null)"
            />
            <x-custom-input 
                name="description" 
                label="Deskripsi"
                placeholder="ex. Utang diperlukan untuk pembelin barang A"
                type="description"
                :value="old('description', $debt->description ?? null)"
            />  
        </div>
    </div>

    <div class="flex px-4 w-1/2 gap-4">
        <x-button_submit label="Simpan" type="submit" />
        <x-button_submit type="cancel" />
    </div>
</form>

@endsection
