<div class="flex items-center">
    <input 
        type="checkbox"
        id="{{ $for }}"
        name="{{ $name }}"
        value="{{ $value }}"
        {{ $checked ? 'checked' : '' }}
        class="w-6 h-6 text-[#000C66] border-gray-500 rounded-sm focus:ring-blue-500" 
        >
    <label for="{{ $for }}" class="ms-2 text-md text-black">{{ $label }}</label>
</div>