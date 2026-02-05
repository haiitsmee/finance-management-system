@extends('components.layout')

@section('title', 'Tambah Admin')

@section('content')
    <div class="flex flex-col">
        <h1 class="text-3xl text-black">Tambah Admin</h1>

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

        <form action="{{ route('superadmin.manajemen-admin.store') }}" method="POST" class="mt-10">
            @csrf
            <div class="flex gap-10">
                <div class="flex flex-col gap-5">
                    <x-form.input for="tanggal" type="text" label="Tanggal ditambahkan" placeholder="" :readonly="true"
                        :required="true" :value="$transactionDateFormatted" width="w-70" />
                    <x-form.input for="username" type="text" label="Username" placeholder="Username" :readonly="false"
                        :required="true" value="" width="w-70" />
                    <div class="relative">
                        <x-form.input for="password" type="password" label="Password" placeholder="Password"
                            :readonly="false" :required="true" value="" width="w-full" />
                        <button type="button" id="togglePassword"
                            class="absolute right-3 top-[60%] -translate-y-1/2 text-gray-600 cursor-pointer">
                            <x-tabler-eye-off id="eyeIconOff1" class="w-7 h-7 hidden" />
                            <x-tabler-eye id="eyeIconOn1" class="w-7 h-7" />
                        </button>
                    </div>

                    <div class="relative">
                        <x-form.input for="password_confirmation" type="password" label="Konfirmasi Password"
                            placeholder="Konfirmasi Password" :readonly="false" :required="true" value=""
                            width="w-70" />
                        <button type="button" id="togglePasswordConfirmation"
                            class="absolute right-3 top-[60%] -translate-y-1/2 text-gray-600 cursor-pointer">
                            <x-tabler-eye-off id="eyeIconOff2" class="w-7 h-7 hidden" />
                            <x-tabler-eye id="eyeIconOn2" class="w-7 h-7" />
                        </button>
                    </div>

                    <div class="flex gap-2">
                        <x-forms.button type="submit" value="Submit"
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
                                <option value="" selected disabled>Pilih role</option>
                                <option value="admin">Admin</option>
                                <option value="superadmin">Superadmin</option>
                            </x-slot:option>
                        </x-form.select>
                    </div>
                    <h2 class="text-black text-left text-md mb-4">Divisi Terkait</h2>
                    <div class="grid grid-cols-2 gap-3" id="business-wrapper">
                        @foreach ($businesses as $business)
                            <x-forms.checkbox for="{{ $business->name }}" name="businesses[]" value="{{ $business->id }}"
                                label="{{ $business->name }}" :checked="false" />
                        @endforeach
                    </div>

                    <div id="no-business-message" class="hidden text-gray-600 italic">
                        Tidak perlu memilih divisi terkait untuk superadmin.
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const roleSelect = document.getElementById('role')
            const businessWrapper = document.getElementById('business-wrapper')
            const noBusinessMessage = document.getElementById('no-business-message')

            roleSelect.addEventListener('change', function() {
                if (this.value === 'superadmin') {
                    businessWrapper.classList.add('hidden')
                    noBusinessMessage.classList.remove('hidden')
                } else {
                    businessWrapper.classList.remove('hidden')
                    noBusinessMessage.classList.add('hidden')
                }
            })
        })

        document.addEventListener('DOMContentLoaded', () => {
            const toggles = [{
                    input: document.getElementById('password'),
                    button: document.getElementById('togglePassword'),
                    eyeOn: document.getElementById('eyeIconOn1'),
                    eyeOff: document.getElementById('eyeIconOff1')
                },
                {
                    input: document.getElementById('password_confirmation'),
                    button: document.getElementById('togglePasswordConfirmation'),
                    eyeOn: document.getElementById('eyeIconOn2'),
                    eyeOff: document.getElementById('eyeIconOff2')
                }
            ];

            toggles.forEach(({
                input,
                button,
                eyeOn,
                eyeOff
            }) => {
                if (!input || !button) return;

                button.addEventListener('click', () => {
                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';

                    eyeOn.classList.toggle('hidden');
                    eyeOff.classList.toggle('hidden');
                });
            });
        });
    </script>
@endsection
