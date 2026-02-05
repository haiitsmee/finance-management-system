@props([
    'id' => 'financeTable',
    'headers' => [],
    'data' => [],
    'hasDetail' => false,
    'hasPagination' => false,
    'pagination' => null,
    'hasAction' => false, 
    'currencyColumns' => ['income', 'outcome', 'revenue', 'csr', 'total', 'amount'],
    'trendColumn' => 'trend',
    'persentageColumn' => 'persentage',
    'isPaidColumn' => 'is_paid',
    'paidValueClass' => 'text-custom-teal',
    'negativeValueClass' => 'text-red-500',
    'positiveValueClass' => 'text-green-500',
    'hasHref' => false,
    'hasDateRangeRedirect' => false,
    'href' => null,
    'label' => null,
    'routeName' => [],
    'params'=> [],
    'param_id' => $params['id'] ?? $params['tanggal'] ?? null,
    'businesses' => $params['businesses'] ?? null,
    'superAdmin' => false,
])


<div class="container mx-auto mb-8 px-4">
    <label for="{{ $id }}" class="mb-4 py-3 items-start">{{$label}}</label>
    <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-100 mt-2">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-white">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-6 py-3 text-left text-sm font-medium text-custom-light-gray tracking-wider">
                            {{ $header['label'] }}
                        </th>       
                    @endforeach
                    @if($hasDetail)
                        <th class="px-6 py-3 text-left text-sm font-medium text-custom-light-gray tracking-wider">
                            Detail
                        </th>
                    @if($hasAction)
                        <th class="px-6 py-3 text-left text-sm font-medium text-custom-light-gray tracking-wider">
                            Aksi
                        </th>
                    
                    @endif
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($data as $item)
                    <tr class="hover:bg-gray-50">
                        @foreach($headers as $header)
                            @php
                                $value = $item[$header['key']] ?? '';
                                $isCurrency = in_array($header['key'], $currencyColumns);
                                $formattedValue = $isCurrency ? formatCurrency($value) : $value;
                                $cellClasses = 'px-6 py-4 whitespace-nowrap text-sm ';
                                
                                if ($header['key']){
                                    $cellClasses .= 'font-medium text-custom-dark-gray';
                                } 

                                
                                if (in_array($header['key'], $currencyColumns)) {
                                    $cellClasses .= ' ' . ($value < 0 ? $negativeValueClass : 'text-black');
                                }
                                
                                if ($header["key"] === $trendColumn){
                                    $formattedValue = $value . '%';
                                    $cellClasses .= ' ' . ($value == 0 ? 'text-black' : ($value < 0 ? $negativeValueClass : $positiveValueClass));
                                    $formattedValue = ($value >= 0 ? "↑ " . $value . '%' : "↓ " . $value . '%');
                                }
                                if ($header["key"] === $persentageColumn){
                                    $formattedValue = $value . ' %';
                                    $cellClasses .= 'text-black';
                                }
                                if ($header["key"] === $isPaidColumn){
                                    $formattedValue = $value ? 'Sudah Dibayar' : 'Belum Dibayar';
                                    $cellClasses .= ' ' . ($value ? $paidValueClass : $negativeValueClass);
                                } 
                            @endphp
                            
                            <td class="{{ $cellClasses }}">
                                {{ $formattedValue }}
                            </td>
                        @endforeach 
                        
                        @if($hasDetail)
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route($href, ['businesses'=>$businesses, 'id' => $item['id']]) }}" class="bg-gray-800 rounded-md text-white px-3 py-1 hover:bg-gray-500">
                                    Detail
                                </a>
                            </td>
                        
                        @endif

                        @if($hasHref)
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route($href, ['businesses'=>$businesses, 'id' => $item['date']]) }}" class="bg-gray-800 rounded-md text-white px-3 py-1 hover:bg-gray-500">
                                    Lihat
                                </a>
                            </td>
                        @endif

                        @if ($hasDateRangeRedirect)
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route($href, ['businesses'=>$businesses, 'start' => $item['start'], 'end' => $item['end']]) }}" class="bg-gray-800 rounded-md text-white px-3 py-1 hover:bg-gray-500">
                                    Lihat
                                </a>
                            </td>
                        @endif
                        
                        @if ($superAdmin)
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route($routeName, ['businesses'=>$item['business']]) }}" class="bg-gray-800 rounded-md text-white px-3 py-1 hover:bg-gray-500">
                                Lihat
                            </a>
                            </td>
                        @endif

                        @if($hasAction)
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <div class="flex gap-3 items-center">
                                <x-table.button-table :submit="false"
                                    redirect="{{ route($routeName['edit'], ['businesses'=>$businesses, 'id' => $item['id']]) }}" color="" value="Edit"
                                    type="edit" />
                                <form action="{{ route($routeName['destroy'],['businesses'=>$businesses, 'id' => $item['id']]) }}" method="POST">
                                    @csrf
                                    @method("DELETE")
                                    <x-table.button-table :submit="true" redirect="" color="" value="Delete"
                                        type="delete" />
                                </form>
                            </div>
                            </td>
                        
                        @endif

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
