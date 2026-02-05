@props([
    'headers' => [],
    'data' => [],
    'hasActions' => false,
    'currencyColumns' => ['income', 'outcome', 'revenue', 'csr', 'total', 'ammount'],
    'trendColumn' => 'trend',
    'persentageColumn' => 'persentage',
    'negativeValueClass' => 'text-red-500',
    'positiveValueClass' => 'text-green-500',
    'href' => null,
])

<div class="container mx-auto mt-8 px-4">
    <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-white">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-6 py-3 text-left text-sm font-medium text-custom-light-gray tracking-wider">
                            {{ $header['label'] }}
                        </th>
                    @endforeach
                    @if($hasActions)
                        <th class="px-6 py-3 text-left text-sm font-medium text-custom-light-gray tracking-wider">
                            Aksi
                        </th>
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
                            @endphp
                            
                            <td class="{{ $cellClasses }}">
                                {{ $formattedValue }}
                            </td>
                        @endforeach
                        
                        @if($hasActions)
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ $href }}" class="bg-gray-800 rounded-md text-white px-3 py-1 hover:bg-gray-500">
                                Detail
                            </a>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
