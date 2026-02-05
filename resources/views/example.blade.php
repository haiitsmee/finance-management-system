@extends('components.layout')

@section('content')
    <x-range-picker 
    nameStart="check_in" 
    nameEnd="check_out"
    id="reportRangePicker"
    class="form-control"
    />
@endsection