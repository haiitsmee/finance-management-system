@extends("components.layout")
@section('title', content: 'Laporan Keuangan Superadmin')

@php
    $role = auth()->user()->role;
@endphp

@section('content')

    @php
        $transactionHeaders = [
            ['key' => 'name', 'label' => 'Nama Divisi'],
            ['key' => 'income', 'label' => 'Total Pemasukan'],
            ['key' => 'outcome', 'label' => 'Total Pengeluaran'],
            ['key' => 'revenue', 'label' => 'Laba'],
        ];

        $bulanMap = [
            'Januari' => 1,
            'Februari' => 2,
            'Maret' => 3,
            'April' => 4,
            'Mei' => 5,
            'Juni' => 6,
            'Juli' => 7,
            'Agustus' => 8,
            'September' => 9,
            'Oktober' => 10,
            'November' => 11,
            'Desember' => 12,
        ];
    @endphp

    <div class="flex flex-col items-center min-w-full">
        <div class="flex self-start">

            <x-custom-input type="option" id="month-filter" name="month" label="Pilih Bulan" :value="[
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ]" :placeholder="$month ?? 'Pilih Bulan'" />

            <a href="{{ $month ? route('superadmin.laporan-keuangan-all.show', ['month' => $month]) : '#' }}"
                class="flex w-max h-10 p-4 mx-4 mt-7 items-center self-center rounded-md border {{ $month ? 'bg-[#050A30] hover:bg-gray-200 hover:text-[#050A30]' : 'bg-gray-400 cursor-not-allowed' }} text-white text-md">
                <span>Lihat Laporan Keuangan</span> </a>
        </div>

        <div class="flex flex-col min-w-full mx-auto z-[-1]">
            <x-table.data-table>
                <x-slot:header>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Divisi</th>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Pemasukan</th>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Pengeluaran</th>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Laba Bersih</th>
                </x-slot:header>
                <x-slot:row>
                    @foreach ($data as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction['name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">@currency($transaction['income'])</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">@currency($transaction['outcome'])</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">@currency($transaction['revenue'])</td>
                        </tr>
                    @endforeach
                </x-slot:row>
            </x-table.data-table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const monthFilter = document.getElementById("month-filter");

            monthFilter.addEventListener("change", function () {
                let month = this.value;

                let url = "{{ route('superadmin.laporan-keuangan-all.index') }}";
                window.location.href = url + `?month=${month}`; 
            });
        });
    </script>

@endsection