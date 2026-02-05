@extends('components.layout')

@section('title', 'Laporan Keuangan')

@section('content')
 <div class="bg-[#ffffff] min-h-screen p-8">
        <div class="max-w-7xl mx-auto">
            {{-- Main Title --}}
            <h1 class="text-[#000000] text-xl font-medium mb-8">Tabel Transaksi Bulan {{ $month ?? '' }}</h1>

            {{-- Transaction Table --}}
            <div class="mb-12">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#f5f5f5]">
                            <th class="text-left py-4 px-2 text-[#000000] font-medium">Divisi</th>
                            <th class="text-left py-4 px-2 text-[#000000] font-medium">Pemasukan</th>
                            <th class="text-left py-4 px-2 text-[#000000] font-medium">Pengeluaran</th>
                            <th class="text-left py-4 px-2 text-[#000000] font-medium">Laba Bersih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($data) && count($data) > 0)
                            @foreach($data as $transaction)
                            <tr class="border-b border-[#f5f5f5]">
                                <td class="py-6 px-2 text-[#000000]">{{ $transaction['name'] }}</td>
                                <td class="py-6 px-2 text-[#000000]">Rp {{ number_format($transaction['income'], 0, ',', '.') }}</td>
                                <td class="py-6 px-2 text-[#000000]">Rp {{ number_format($transaction['outcome'], 0, ',', '.') }}</td>
                                <td class="py-6 px-2 text-[#000000] {{ $transaction['revenue'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($transaction['revenue'], 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="py-6 px-2 text-center text-[#000000]">Tidak ada data transaksi untuk bulan ini</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if(isset($summary))
            <div class="mb-8">
                <h2 class="text-[#000000] text-lg font-medium mb-8">Ringkasan Keuangan Bulan {{ $month ?? 'Bulan' }}</h2>

                <div class="space-y-6">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-[#9f9f9f] font-medium">Komponen</span>
                        <span class="text-[#9f9f9f] font-medium">Total</span>
                    </div>

                    <div class="flex justify-between items-center py-2">
                        <span class="text-[#9f9f9f]">Total Pemasukan</span>
                        <span class="text-[#000000]">Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2">
                        <span class="text-[#9f9f9f]">Total Pengeluaran</span>
                        <span class="text-[#000000]">Rp {{ number_format($summary['total_outcome'], 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center py-2">
                        <span class="text-[#9f9f9f]">Laba Bersih</span>
                        <span class="text-[#000000] {{ $summary['total_revenue'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <div class="flex justify-end">
                <a href="{{ route('superadmin.laporan-keuangan-all.cetak-pdf') . '?' . http_build_query(['month' => $month]) }}" class="border border-[#9f9f9f] text-[#000000] px-6 py-2 rounded-md hover:bg-[#f5f5f5] transition-colors">
                    Cetak PDF
                </a>
            </div>
        </div>
    </div>
@endsection