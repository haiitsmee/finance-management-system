@props(['title', 'value', 'color', 'icon', 'isCsr' => false])

<div class="relative bg-white rounded-lg shadow-md overflow-hidden flex">
    <div class="w-2 z-10 bg-[{{ $color }}]"></div>
    <div class="p-4 flex-1">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-gray-700 text-sm font-medium">{{ $title }}</h3>
            <div class="text-[{{ $color }}]">
                <x-tabler-icons :name="$icon" />
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-900 mb-2">
            @if ($isCsr)
                <span>{{ $value . ' %' }}</span>
            @else
                {{ formatCurrency($value) }}            
            @endif
        </div>
    </div>
</div>
