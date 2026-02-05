@extends('components.layout')
@section('title', 'Dashboard Superadmin') 
  
@section('content')

<!-- headers for tables -->
@php
        $trendHeaders = [
            ['key' => 'month', 'label' => 'Bulan'],
            ['key' => 'income', 'label' => 'Pendapatan'],
            ['key' => 'outcome', 'label' => 'Pengeluaran'],
            ['key' => 'revenue', 'label' => 'Laba Bersih'],
            ['key' => 'trend', 'label' => 'Tren Laba'],
        ];
        
        $businessSumaryHeader = [
            ['key' => 'name', 'label' => 'Nama Divisi'],
            ['key' => 'income', 'label' => 'Pendapatan'],
            ['key' => 'outcome', 'label' => 'Pengeluaran'],
            ['key' => 'revenue', 'label' => 'Laba Bersih'],
        ];
        
@endphp

<!-- cards -->
<div class="container pt-8 flex min-w-full items-center justify-center px-auto">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 w-full max-w-6xl">
        @foreach($summaryData as $card)
            <x-card-summary
                title="Total Pendapatan" 
                value="{{ $card['value1'] }}" 
                time="{{ $card['time'] }}" 
                color="[#F83C31]" 
                icon="wallet" 
            />

            <x-card-summary
                title="Total Pengeluaran" 
                value="{{ $card['value2'] }}" 
                time="{{ $card['time'] }}" 
                color="light-red-custom" 
                icon="wallet" 
            />

            <x-card-summary
                title="Total Keuntungan" 
                value="{{ $card['value3'] }}" 
                time="{{ $card['time'] }}" 
                color="blue-custom" 
                icon="pie-chart" 
            />

            <x-card-summary
                title="Persentase CSR" 
                value="{{ $card['value4'] }}" 
                time="{{ $card['time'] }}" 
                color="teal-custom"
                icon="pie-chart" 
                :isCsr="true"
            />
        @endforeach
    </div>
</div>



<!-- line chart -->
<div class="w-full max-w-[800px] mx-auto py-8" style="aspect-ratio: 2/1;">
    <x-chart 
        id="lineChart" 
        type="line" 
        :data="$lineChartData" 
        :options="[
            'responsive' => true,
            'maintainAspectRatio' => true,
            'interaction' => [
                'mode' => 'index'
            ]
        ]"
    />
</div>

<!-- trend details table -->
<x-finance-table 
    :label="'Trend Laba Bersih'"
    :headers="$trendHeaders"
    :data="$trendData"
/>

<div class="container mx-auto mb-8 px-4">
    <!-- range picker -->
    <x-range-picker 
    nameStart="start" 
    nameEnd="end"
    id="reportRangePicker"
    class="form-control"
    routeName=".dashboard.handleRange"
    :latestInput="$start . ' to ' . $end"
    /> 
</div>

<div class="w-full max-w-[800px] mx-auto py-8" style="aspect-ratio: 2/1;">
    <x-chart 
        id="barChart" 
        type="bar" 
        :data="$businessSumary" 
        :options="[
            'responsive' => true,
            'maintainAspectRatio' => true,
            'scales' => [
                'y' => [
                    'beginAtZero' => true
                ]
            ]
        ]"
    />
</div>

<x-finance-table 
:label="'Tabel Divisi'"
:headers="$businessSumaryHeader"
:data="$profitData"
:superAdmin="true"
routeName="superadmin.report.index"
/>

<div class="container mx-auto mb-8 px-4">
   <label for="csrTable">Tabel CSR</label>
    <div id="csrTable" class="overflow-x-auto rounded-lg shadow-sm border border-gray-100 mt-2">
            <table class="min-w-full divide-y divide-gray-200 bg-white">
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

@endsection