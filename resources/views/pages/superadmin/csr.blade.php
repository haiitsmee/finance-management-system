@extends('components.layout')

@section('title', 'CSR')

@section('content')
<div class="min-h-screen bg-[#f1f1f1] p-8">   
    <div class="max-w-7xl mx-auto space-y-8">
            <div class="flex items-center gap-4 w-full md:flex-row flex-col">
            <h1 class="font-bold mb-0">
                CSR saat ini : {{ $percentage }}
            </h1>

            <form action="{{ route('superadmin.csr-setting.update') }}" method="POST" class="flex items-center gap-3">
                @csrf
                <input
                    name="percentage"
                    type="number"
                    step="0.01"
                    min="0"
                    max="100"
                    placeholder="koma gunakan titik (.)"
                    value="{{ old('percentage', $percentage) }}"
                    class="border border-[#d9d9d9] rounded px-3 py-2 w-40 focus:outline-none focus:ring focus:ring-[#050a30]/30"
                />
                <button type="submit" class="bg-[#050a30] text-white px-4 py-2 rounded text-sm font-medium">
                    Simpan
                </button>
            </form>
        </div>

        {{-- Notifikasi --}}
        @if ($errors->has('percentage'))
            <p class="text-red-600 mt-2">{{ $errors->first('percentage') }}</p>
        @endif

        @if (session('success'))
            <p class="text-green-600 mt-2">{{ session('success') }}</p>
        @endif
        <!-- Top Section - Income and Withdrawal Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- CSR Income Table -->
            <div class="bg-[#ffffff] rounded-lg p-6 shadow-sm ">
                <div class="flex items-center justify-between mb-6 sm:flex-row flex-col">
                    <h2 class="text-lg font-medium text-[#010101]">Tabel Pemasukan CSR</h2>
                    <a href="{{ route('superadmin.csr.create-income.add') }}" class="bg-[#050a30] text-white sm:px-2 sm:py-2 rounded text-sm font-medium flex items-center gap-2 ">
                        <span class="text-lg">+</span>
                        Tambah Pemasukan
                    </a>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4 pb-2 border-b border-[#d9d9d9]">
                        <div class="text-[#818181] text-sm font-medium">Sumber</div>
                        <div class="text-[#818181] text-sm font-medium">Nominal</div>
                    </div>

                    @foreach($incomeData as $income)
                    <div class="grid grid-cols-2 gap-4 py-3">
                        <div class="text-[#010101] font-medium">{{ $income['source'] }}</div>
                        <div class="text-[#010101] font-medium">{{ $income['amount'] }}</div>
                    </div>
                    @endforeach

                    <div class="grid grid-cols-2 gap-4 py-3 border-t border-[#d9d9d9] font-semibold">
                        <div class="text-[#010101]">Total :</div>
                        <div class="text-[#010101]">{{ $totalIncome }}</div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <form action="{{ route('superadmin.csr.withdraw.all') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-[#2c2c2c] text-white px-6 py-2 rounded text-sm font-medium">
                            Tarik
                        </button>
                    </form>               
                </div>
            </div>

            <!-- Withdrawal History Table -->
            <div class="bg-[#ffffff] rounded-lg p-6 shadow-sm">
                <h2 class="text-lg font-medium text-[#010101] mb-6">Tabel Riwayat Penarikan</h2>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4 pb-2 border-b border-[#d9d9d9]">
                        <div class="text-[#818181] text-sm font-medium">Tanggal</div>
                        <div class="text-[#818181] text-sm font-medium">Nominal</div>
                    </div>

                    @foreach($withdrawalHistory as $withdrawal)
                    <div class="grid grid-cols-2 gap-4 py-3">
                        <div class="text-[#010101] font-medium">{{ $withdrawal['date'] }}</div>
                        <div class="text-[#010101] font-medium">{{ $withdrawal['amount'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- CSR Distribution Table -->

        <div class="container mx-auto mb-8 px-4">
            <label for="csrTable">Tabel CSR</label>
                <div id="csrTable" class="overflow-x-auto rounded-lg shadow-sm border border-gray-100 mt-2">
                        <table class="min-w-full divide-y divide-gray-200 bg-white">
                            <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-medium text-[#010101]">Tabel Penyaluran CSR</h2>
                            <a href="{{ route('superadmin.csr.create-distribution.add') }}" class="bg-[#050a30] text-white px-4 py-2 rounded text-sm font-medium flex items-center gap-2">
                                <span class="text-lg">+</span>
                                Tambah Penyaluran
                            </a>
                        </div>
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-custom-light-gray tracking-wider">Kegiatan</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-custom-light-gray tracking-wider">Tujuan</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-custom-light-gray tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-custom-light-gray tracking-wider">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-custom-light-gray tracking-wider">Aksi</th>
                                </tr>
                                
                            </thead>
                
                            <tbody class="divide-y divide-gray-100">
                                @foreach($distributionData as $distribution)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">{{ $distribution['activity'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">{{ $distribution['purpose'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">{{ $distribution['date'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">{{ $distribution['amount'] }}</td>
                                        <td>
                                            <a href="{{ route('superadmin.csr.distribution.detail', [$distribution['id']]) }}" class="bg-[#2c2c2c] text-white px-4 py-1.5 rounded text-sm font-medium">Detail</a >
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            
                        </table>
                </div>
            </div>      
    </div>
</div>

@if ($errors->has('error'))
    <div class="text-red-500 text-sm mt-2">
        {{ $errors->first('amount') }}
    </div>
@endif
@endsection
