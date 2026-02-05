@extends('components.layout')

@section('title', 'Detail Penyaluran CSR')

@section('content')
    <div class="flex flex-col p-6">
        <h1 class="text-2xl text-black font-bold mb-6">
            Detail Penyaluran CSR: <strong>{{ session('current_business_name') ?? 'Superadmin' }}</strong>
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
            <div class="flex flex-col gap-4 bg-white p-6 rounded-lg shadow-sm">
                <div>
                    <span class="text-gray-600 block mb-1">Tanggal Penyaluran</span>
                    <span class="font-bold text-lg">{{ \Carbon\Carbon::parse($csrData->date)->format('d/m/Y') }}</span>
                </div>
                
                <div>
                    <span class="text-gray-600 block mb-1">Tujuan Penyaluran</span>
                    <span class="font-bold text-lg">{{ $csrData->purpose }}</span>
                </div>
                
                <div>
                    <span class="text-gray-600 block mb-1">Deskripsi</span>
                    <span class="font-bold text-lg">{{ $csrData->description ?? 'Tidak ada deskripsi' }}</span>
                </div>
            </div>

            <div class="flex flex-col gap-4 bg-white p-6 rounded-lg shadow-sm">
                <div>
                    <span class="text-gray-600 block mb-1">Nominal Penyaluran</span>
                    <span class="font-bold text-lg text-green-600">Rp {{ number_format($csrData->amount, 0, ',', '.') }}</span>
                </div>
                
                <div>
                    <span class="text-gray-600 block mb-1">Kegiatan</span>
                    <span class="font-bold text-lg">{{ $csrData->activity }}</span>
                </div>
                
                <div>
                    <span class="text-gray-600 block mb-1">Status Dokumen</span>
                    <span class="font-bold text-lg {{ $fileExists ? 'text-green-600' : 'text-red-600' }}">
                        {{ $fileExists ? 'Tersedia' : 'Tidak tersedia' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
            <span class="text-gray-600 block mb-4 text-lg font-semibold">Bukti Penyaluran</span>
            
            @if ($fileExists)
                <div class="border border-gray-300 rounded-lg p-4 flex items-center justify-center">
                    @if (in_array(pathinfo($csrData->document_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ asset('storage/' . $csrData->document_path) }}" 
                             alt="Bukti Penyaluran" 
                             class="max-w-full h-auto max-h-96 rounded-lg">
                    @else
                        <div class="text-center">
                            <div class="text-4xl mb-2">📄</div>
                            <p class="text-gray-600">Dokumen: {{ basename($csrData->document_path) }}</p>
                            <a href="{{ asset('storage/' . $csrData->document_path) }}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                Lihat Dokumen
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="border border-gray-300 rounded-lg p-8 text-center">
                    <span class="text-gray-400">Tidak ada bukti penyaluran yang tersedia</span>
                </div>
            @endif
        </div>

        <div class="flex justify-end gap-4">
            <!-- <a href="{{ route('superadmin.csr.distribution.print', $csrData->id) }}" 
               target="_blank"
               class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                📄 Cetak PDF
            </a> -->
            <a href="{{ route('superadmin.csr.distribution.print', ['id' => $csrData->id]) }}" 
               class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Cetak Halaman
            </a>
            <a href="{{ route('superadmin.csr.index') }}" 
               class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                Kembali
            </a>
        </div>
    </div>
@endsection