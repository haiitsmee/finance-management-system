@extends('components.layout')
@section('title', 'Dashboard Admin Divisi') 
  
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
        
        

        $outcomesHeaders = [
            ['key' => 'category', 'label' => 'Kategori Pengeluaran'],
            ['key' => 'total', 'label' => 'Jumlah'],
            ['key' => 'persentage', 'label' => 'Persentase']
        ];
        $incomeHeaders = [
            ['key' => 'category', 'label' => 'Kategori Pemasukan'],
            ['key' => 'total', 'label' => 'Jumlah'],
            ['key' => 'persentage', 'label' => 'Persentase'],

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
                color="#F83C31" 
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
                :isCsr="true"
                icon="pie-chart" 
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

<!-- pie chart and range picker -->
<div class="flex justify-around px-4 py-8">
    <x-chart
            id="incomeChart"
            type="pie"
            :data="$incomeChart"
            :options="[
                'responsive' => true,
                'plugins' => [
                    'legend' => [
                        'position' => 'right',
                    ],
                    'tooltip' => [
                        'enabled' => true,
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Distribusi Pendapatan',
                    ],
                ],
            ]"
    />
    <x-chart
            id="expenseChart"
            type="pie"
            :data="$expenseChart"
            :options="[
                'responsive' => true,
                'plugins' => [
                    'legend' => [
                        'position' => 'right',
                    ],
                    'tooltip' => [
                        'enabled' => true,
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Distribusi Pengeluaran',
                    ],
                ],
            ]"
    />

    <!-- range picker -->
    <x-range-picker 
    :businesses="$businesses"
    nameStart="start" 
    nameEnd="end"
    id="reportRangePicker"
    class="form-control"
    routeName=".handleRange"
    :latestInput="$start . ' to ' . $end"
    />
</div>

<!-- outcome details table -->
<x-finance-table 
:label="'Detail Pemasukan'"
:headers="$incomeHeaders"
:data="$incomeData"
:hasDateRangeRedirect="true"
href="{{ auth()->user()->role }}.catat-pemasukan.index"
:params="[
    'businesses' => session('current_business'),
    'id' => ':id'
    ]"
    />
    
<x-finance-table 
    :label="'Detail Pengeluaran'"
    :headers="$outcomesHeaders"
    :data="$expenseData"
    :hasDateRangeRedirect="true"
    href="{{ auth()->user()->role }}.catat-pengeluaran.index"
    :params="[
        'businesses' => session('current_business'),
        'id' => ':id'
        ]"
    />

@endsection