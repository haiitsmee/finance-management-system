@extends('components.layout')

@section('title', 'Manajemen Admin')

@section('content')
    <div class="flex flex-col">
        <h1 class="text-3xl text-black">List Admin</h1>
        <x-button-add direct="/superadmin/manajemen-admin/create" value="Tambah Admin" accesskey="" />

        <x-table.data-table>
            <x-slot:header>
                <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Username</th>
                <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Tanggal Ditambahkan</th>
                <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Admin</th>
                <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Aksi</th>
            </x-slot:header>
            <x-slot:row>
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex gap-3 items-center">
                                <img src="{{ asset('images/photo-profile.png') }}" alt="Photo Profile"
                                    class="w-5 h-5 rounded-full object-cover">
                                <span>{{ ucwords($user->username) }}</span>
                            </div>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ date($user->created_at) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center gap-2 text-black">
                                @forelse ($user->businesses as $business)
                                    <span   
                                        class="px-4 py-1 rounded-2xl text-xs {{ $colors[$business->name] ?? 'bg-gray-500' }}">{{ $business->name }}</span>
                                @empty
                                    <span   
                                        class="px-4 py-1 rounded-2xl text-xs {{ $colors[$business->name] ?? 'bg-gray-500' }}">Superadmin</span>
                                @endforelse ($user->businesses as $business)
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex gap-3 items-center">
                                <x-table.button-table :submit="false"
                                    redirect="{{ route('superadmin.manajemen-admin.edit', $user->slug) }}" color="" value="Edit"
                                    type="edit" />
                                <form action="{{ route('superadmin.manajemen-admin.destroy', $user->slug) }}" method="POST">
                                    @csrf
                                    @method("DELETE")
                                    <x-table.button-table :submit="true" redirect="" color="" value="Delete"
                                        type="delete" />
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-slot:row>
        </x-table.data-table>
    </div>
@endsection