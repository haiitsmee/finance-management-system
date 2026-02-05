<div class="flex flex-col gap-1">
    <label for="{{ $for }}">{{ ucwords($label) }}</label>
    <span class="text-gray-500 italic text-xs">* {{ $note }}</span>
    <input type="file" 
        id="{{ $for }}" 
        name="{{ $for }}" 
        {{ $required ? 'required' : '' }}
        class="w-full px-4 py-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-300 {{ $width }}"
        > 
</div>
