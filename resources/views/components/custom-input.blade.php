@props ([
    'type' => 'text', // text, password, option, description, document, date
    'id' => 'customInput',
    'name' => 'customInput',
    'label' => 'Custom Input',
    'value' => null,
    'placeholder' => 'Enter value',
    'editable' => true,
    'selected' => null,
])

@if ($type === 'text' || $type === 'password' || $type === 'date' || $type === 'number')
    <div class="mb-4">
        <label for="{{ $id }}" class="block text-sm font-large text-gray-800 mb-4">{{ $label }}</label>
        <input 
            type="{{ $type }}" 
            id="{{ $id }}" 
            name="{{ $name }}"
            value="{{ $value }}"   
            placeholder="{{ $placeholder }}" 
            class="w-full px-4 py-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-300 {{ $editable ? '' : 'cursor-not-allowed bg-gray-200' }}"
            {{ $editable ? '' : 'readonly' }} 
        />
    </div>
@elseif ($type === 'harga')
    <div class="mb-4">
        <label for="harga" class="block text-sm font-large text-gray-800 mb-4">{{ $label }}</label>
        <input type="text" id="harga_display" class="w-full px-4 py-4 border border-gray-300 rounded-lg" placeholder="Masukkan harga">
        <input type="hidden" id="harga" name="{{ $name }}">
    </div>
@elseif($type === 'option')
    <div class="mb-4">
        <label for="{{ $id }}" class="block text-sm font-large text-gray-800 mb-4">{{ $label }}</label>
        <select 
            id="{{ $id }}" 
            name="{{ $name }}" 
            class="w-full px-4 py-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-300"
        >
            <option value="" disabled {{ $selected ? '' : 'selected' }}>{{ $placeholder }}</option>
            @foreach ($value as $item)
                <option value="{{ $item }}" {{ $item == $selected ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
    </div>
@elseif ($type === 'document')
    <div class="mb-4">
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700  mb-1">{{ $label }}</label>
        <input type="file" name="{{ $name }}" id="{{ $id }}" class="w-full px-4 py-4 border bg-gray-200 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-300" />
    </div>
@elseif ($type === 'description')
    <div class="mb-4">
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
        <textarea 
            class="w-full px-4 py-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-300" 
            name="{{ $name }}" 
            id="{{ $id }}" 
            placeholder="{{ $placeholder }}">{{ $value }}</textarea> {{-- <<< isi value --}}
    </div>
@endif

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const hargaDisplay = document.getElementById('harga_display');
    const hargaHidden = document.getElementById('harga');

    function formatRupiah(angka) {
      return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    hargaDisplay.addEventListener('input', function (e) {
      let value = e.target.value.replace(/\D/g, '');
      hargaHidden.value = value; // Simpan angka murni
      e.target.value = value ? 'Rp ' + formatRupiah(value) : '';
    });
  });
</script>