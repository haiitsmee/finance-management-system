@extends("components.layout")
@section('title', content: 'Laporan Keuangan')

@php
    $role = auth()->user()->role;
@endphp

@section('content')

    @php
        $transactionHeaders = [
            ['key' => 'date', 'label' => 'Tanggal Transaksi'],
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

            <a href="{{ $month ? route($role . '.' . 'report.show', ['businesses' => session('current_business'), 'date' => $month]) : '#' }}"
                class="flex w-max h-10 p-4 mx-4 mt-7 items-center self-center rounded-md border {{ $month ? 'bg-[#050A30] hover:bg-gray-200 hover:text-[#050A30]' : 'bg-gray-400 cursor-not-allowed' }} text-white text-md">
                <span>Lihat Laporan Keuangan</span> </a>
        </div>

        <div class="flex flex-col min-w-full">
            <div class="flex flex-col min-w-full">
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction['date'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">@currency($transaction['income'])</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">@currency($transaction['outcome'])</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">@currency($transaction['revenue'])</td>
                                <td>
                                    <a href="{{ route(auth()->user()->role . '.report.index', ['businesses' => session('current_business'), 'date' => $transaction['date']]) }}"
                                        class="bg-gray-800 rounded-md text-white px-3 py-1 hover:bg-gray-500">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot:row>
                </x-table.data-table>
                <x-table.data-table>
                    <x-slot:header>
                        <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Produk / Jasa</th>
                        <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Jumlah Unit</th>
                        <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Harga per-unit</th>
                        <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Total</th>
                        <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Status</th>
                    </x-slot:header>
                    @if ($detail)
                        <x-slot:row>
                            @foreach ($detail as $income)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $income->transaction_date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ ucwords($income->product) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $income->product_quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">@currency($income->price)</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">@currency($income->total)</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($income->status == 'lunas')
                                            <span
                                                class="px-4 py-1 rounded-2xl text-xs bg-green-300">{{ ucwords($income->status) }}</span>
                                        @else
                                            <span class="px-4 py-1 rounded-2xl text-xs bg-red-300">{{ ucwords($income->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <x-table.button-detail
                                            redirect="{{ route(auth()->user()->role . '.catat-pemasukan.show', ['businesses' => session('current_business'), $income->id]) }}" />
                                    </td>
                                </tr>
                            @endforeach
                        </x-slot:row>
                    @else
                        <x-slot:row></x-slot:row>
                    @endif
                </x-table.data-table>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const monthFilter = document.getElementById("month-filter");

                monthFilter.addEventListener("change", function () {
                    let month = this.value;

                    let url = "{{ route($role . '.' . 'report.index', ['businesses' => session('current_business')]) }}";
                    window.location.href = url + `?month=${month}`;
                });
            });
        </script>

@endsection