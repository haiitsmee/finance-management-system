<div class="flex flex-col gap-1">
    <label for="{{ $for }}">{{ ucwords($label) }}</label>
    <textarea name="{{ $for }}" id="{{ $for }}" rows="{{ $rows }}" cols="{{ $cols }}" placeholder="{{ $placeholder }}"
        class="w-full px-4 py-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-300">{{ $value }}</textarea>
</div>