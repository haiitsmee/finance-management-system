@props([
    'businesses' => null,
    'nameStart' => 'start_date',
    'nameEnd' => 'end_date',
    'id' => 'rangePicker',
    'routeName' => null,
    'options' => [],
    'latestInput' => null,
])

<div class="range-picker-container py-4 gap-4">
<form action="{{ route(auth()->user()->role . $routeName, ['businesses' => $businesses]) }}" method="POST">
    @csrf
        <label for="{{ $id }}" class="py-4">Timeline</label>
        <input 
            type="text" 
            name="{{ $id }}"
            id="{{ $id }}"
            placeholder="{{ $latestInput !== ' to ' ? $latestInput : 'Pilih rentang tanggal' }}"
            class="flatpickr-range"
            data-options="{{ json_encode(array_merge([
                'mode' => 'range',
                'altInput' => true,
                'altFormat' => 'j F Y',
                'dateFormat' => 'Y-m-d'
            ], $options)) }}"
            {{ $attributes }}
        >

        <input type="hidden" name="{{ $nameStart }}" id="{{ $nameStart }}">
        <input type="hidden" name="{{ $nameEnd }}" id="{{ $nameEnd }}">

        <button type="submit" class="btn bg-gray-800 py-3 px-4 rounded-md hover:bg-gray-500 text-white mt-4">
            Terapkan
        </button>
    </form>
   
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const picker = flatpickr("#{{ $id }}", {
            ...JSON.parse(document.getElementById("{{ $id }}").dataset.options),
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    const startDate = instance.formatDate(selectedDates[0], 'Y-m-d');
                    const endDate = instance.formatDate(selectedDates[1], 'Y-m-d');
                    
                    document.getElementById("{{ $nameStart }}").value = startDate;
                    document.getElementById("{{ $nameEnd }}").value = endDate;
                    
                    console.log({
                        start: startDate,
                        end: endDate
                    });
                }
            }
        });
    });
</script>
@endpush