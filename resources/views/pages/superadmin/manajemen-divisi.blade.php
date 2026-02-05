@extends('components.layout')

@section('title', 'Manajemen Divisi')
@section('current_page', 'Manajemen Divisi')

@section('content')
    <div class="flex flex-col">
        <h1 class="text-3xl text-black">List Divisi</h1>
        <x-button-add direct="/superadmin/manajemen-divisi/create" value="Baru" accesskey="" />

        <div class="flex flex-col gap-4">
            @foreach ($businesses as $business)
                <x-business-card :businessAdmins="$business->users" :businessId="$business->id" :businessName="$business->name" business-role="test" />
            @endforeach
        </div>
    </div>
@endsection