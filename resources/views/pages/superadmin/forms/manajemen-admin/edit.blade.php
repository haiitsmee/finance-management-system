@extends('components.layout')

@section('title', 'Edit Admin')

@section('content')
    <div class="flex flex-col">
        <h1 class="text-3xl text-black">Edit Admin</h1>

        @if ($errors->any())
            <div class="flex p-4 mt-3 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                <x-feathericon-info class="w-5 h-5" />
                <span class="sr-only">Kesalahan Inputan</span>
                <div>
                    <span class="font-medium ml-3">Pastikan sesuai dengan di bawah ini:</span>
                    <ul class="mt-1.5 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('superadmin.manajemen-admin.update', $user->slug) }}" method="POST" class="mt-10">
            @csrf
            @method('PUT')
            <div class="flex gap-10">
                <div class="flex flex-col gap-5">
                    <x-form.input for="tanggal" type="text" label="Tanggal ditambahkan" placeholder="" :readonly="true"
                        :required="true" value="{{ date('d F Y') }}" width="w-70" />
                    <x-form.input for="username" type="text" label="Username" placeholder="Username" :readonly="false"
                        :required="false" :value="$user->username" width="w-70" />
                    <x-form.input for="password" type="password" label="Password" placeholder="Password" :readonly="false"
                        :required="false" value="" width="w-70" />
                    <x-form.input for="password_confirmation" type="password" label="Konfirmasi Password"
                        placeholder="Konfirmasi Password" :readonly="false" :required="false" value="" width="w-70" />
                    <div class="flex gap-2">
                        <x-forms.button type="submit" value="Edit"
                            class="w-35 mt-7 bg-[#050A30] hover:bg-gray-200 hover:text-[#050A30] text-white border border-[#050A30]" />
                        <x-forms.button type="button" value="Cancel"
                            onclick="window.location.href='{{ route('superadmin.manajemen-admin.index') }}'"
                            class="cancel-button w-35 mt-7 bg-[#F1F1F1] hover:bg-[#050A30] hover:text-gray-200 text-[#050A30] border border-[#050A30]" />
                    </div>
                </div>
                <div>
                    <div class="mb-6">
                        <x-form.select for="role" label="Role" width="w-70">
                            <x-slot:option>
                                <option value="" disabled>Pilih role</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="superadmin" {{ $user->role === 'superadmin' ? 'selected' : '' }}>Superadmin
                                </option>
                            </x-slot:option>
                        </x-form.select>
                    </div>
                    <h2 class="text-black text-left text-md mb-4">Divisi Terkait</h2>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach ($businesses as $business)
                            <x-forms.checkbox for="{{ $business->name }}" name="businesses[]" value="{{ $business->id }}"
                                label="{{ $business->name }}" :checked="in_array($business->id, $selectedBusinesses)" />
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection