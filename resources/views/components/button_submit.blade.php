@props([
    'label'=> 'Submit',
    'type' => 'submit',])

@if ($type === 'submit')
<button type="submit" class="p-2 rounded-md w-35 mt-7 bg-[#050A30] hover:bg-gray-200 hover:text-[#050A30] text-white border border-[#050A30]">
    {{ $label }} </button>

@else
<button type="button" class="cancel-button p-2 rounded-md w-35 mt-7 bg-[#F1F1F1] hover:bg-[#050A30] hover:text-gray-200 text-[#050A30] border border-[#050A30]" onclick= window.history.back();>
    cancel </button>
@endif

