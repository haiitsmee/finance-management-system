@if ($submit)
    <button class="flex {{ $color }} items-center gap-2 text-white p-1.5 rounded-sm hover:bg-gray-200 transition-all duration-500">
        @if ($type == 'edit')
            <x-tabler-edit class="w-6 h-6 text-blue-700" />
        @elseif ($type == 'delete')
            <x-tabler-trash class="w-6 h-6 text-red-500" />
        @endif
    </button>
@else
    <a href="{{ $redirect }}" class="flex {{ $color }} items-center gap-2 text-white p-1.5 rounded-sm hover:bg-gray-200 transition-all duration-500">
        @if ($type == 'edit')
            <x-tabler-edit class="w-6 h-6 text-blue-700" />
        @elseif ($type == 'delete')
            <x-tabler-trash class="w-6 h-6 text-red-500" />
        @endif
    </a>
@endif