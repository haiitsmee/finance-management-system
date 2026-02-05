<div class="flex flex-col gap-1">
    <label for="{{ $for }}">{{ ucwords($label) }}</label>
    <input type="{{ $type }}" 
        id="{{ $for }}" 
        name="{{ $for }}" 
        placeholder="{{ $placeholder }}"
        @if (!is_null($value)) value="{{ $value }}" @endif
        {{ $required ? 'required' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        class="@if($type === 'password') pr-10 @endif w-full px-4 py-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-300 {{ $width }}"
        > 
</div>
