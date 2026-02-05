@props(['id', 'type' => 'bar', 'data', 'options' => [], 'width' => null, 'height' => null])

<div class="chart-container" style="position: relative; {{ $width ? 'width:' . $width . ';' : '' }} {{ $height ? 'height:' . $height . ';' : '' }}">
    <canvas id="{{ $id }}"></canvas>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('{{ $id }}');
        new Chart(ctx, {
            type: '{{ $type }}',
            data: {!! json_encode($data) !!},
            options: {!! json_encode($options) !!}
        });
    });
</script>
@endpush