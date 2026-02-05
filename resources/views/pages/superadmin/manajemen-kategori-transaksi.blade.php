@extends('components.layout')

@section('title', 'Manajemen Kategori Transaksi')

@section('content')
    <div class="flex flex-col">
        <h1 class="text-3xl text-black">List Kategori</h1>


        @if ($errors->any())
        <div class="flex p-4 mt-3 text-sm text-red-800 rounded-lg bg-red-50"
        role="alert">
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

        <div class="flex flex-col mt-10">
            <form  action="{{ $transactionCategory
                ? route('superadmin.manajemen-kategori.update', [
                    'manajemen_kategori' => $transactionCategory->id
                ])
                : route('superadmin.manajemen-kategori.store')
            }}" 
            method="POST" 
            enctype="multipart/form-data">
            @csrf    

            @isset($transactionCategory)
                @method('PUT')
            @endisset

            <div class="flex gap-10">
                    <div class="flex flex-col gap-3">
                        <div class="flex gap-10">
                            <x-form.input for="judul_kategori" label="Judul Kategori" placeholder="Judul kategori" :readonly="false"
                                :required="true" type="text" value="{{ $transactionCategory?->title }}" width="w-70" />
                            <x-form.select for="tipe_kategori" label="Tipe Kategori" width="w-70">
                                <x-slot:option>
                                    <option value="" disabled {{ !$transactionCategory ? 'selected' : '' }}>Pilih Tipe</option> 
                                    <option value="pemasukan" {{ $transactionCategory?->tags == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                                    <option value="pengeluaran" {{ $transactionCategory?->tags == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                                </x-slot:option>
                            </x-form.select>
                        </div>
                        <div class="flex gap-2">
                            <x-forms.button type="submit" value="Submit" class="w-35 mt-7 bg-[#050A30] hover:bg-gray-200 hover:text-[#050A30] text-white border border-[#050A30]" />
                            @if ($transactionCategory)
                                <x-forms.button type="button" value="Cancel"
                            onclick="window.location.href='{{ route('manajemen-kategori.index') }}'"
                            class="cancel-button w-35 mt-7 bg-[#F1F1F1] hover:bg-[#050A30] hover:text-gray-200 text-[#050A30] border border-[#050A30]" />
                            @else
                             <x-forms.button type="button" value="Cancel" class="cancel-button w-35 mt-7 bg-[#F1F1F1] hover:bg-[#050A30] hover:text-gray-200 text-[#050A30] border border-[#050A30]" />
                            @endif
                        </div>
                    </div>
                </div>
            </form>

            <div class="mt-10 z-[-1]">
                <x-table.data-table>
                <x-slot:header>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Judul Kategori</th>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Tipe Kategori</th>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Aksi</th>
                </x-slot:header>
                <x-slot:row>
                    @foreach ($transactionCategories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ ucwords($category->title) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if ($category->tags == 'pemasukan')
                                    <span class="px-4 py-1 rounded-2xl text-xs bg-green-300">{{ ucwords($category->tags ) }}</span>
                                @else
                                    <span class="px-4 py-1 rounded-2xl text-xs bg-red-300">{{ ucwords($category->tags) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex gap-3 items-center">
                                <x-table.button-table :submit="false"
                                    redirect="{{ route('superadmin.manajemen-kategori.edit', ['manajemen_kategori' => $category->id]) }}" color="" value="Edit"
                                    type="edit" />
                                <form action="{{ route('superadmin.manajemen-kategori.destroy', ['manajemen_kategori' => $category->id]) }}" method="POST">
                                    @csrf
                                    @method("DELETE")
                                    <x-table.button-table :submit="true" redirect="" color="    " value="Delete"
                                        type="delete" />
                                </form>
                            </div>
                        </td>
                        </tr>
                    @endforeach
                </x-slot:row>
            </x-table.data-table>
            </div>
        </div>
    </div>

    <script>
        const cancelBtn = document.querySelector('.cancel-button')

        cancelBtn.addEventListener('click', function () {
                document.querySelector('form').reset();
                totalInput.value = '';
            });
    </script>
@endsection