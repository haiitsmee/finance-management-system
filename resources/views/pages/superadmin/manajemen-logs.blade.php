@extends('components.layout')

@section('title', 'Log Aktifitas')

@section('content')
    <div class="flex flex-col">
        <h1 class="text-3xl text-black">List Log Aktifitas</h1>

        <div class="mt-10">

            <x-table.data-table>
                <x-slot:header>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Admin</th>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Aksi</th>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Aktifitas</th>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Ip Address</th>
                </x-slot:header>
                <x-slot:row>
                    @foreach ($activityLogs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log->created_at }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ ucwords($log->user->username) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log->action }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log->activity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{{ $log->ip_address }}}</td>
                        </tr>
                    @endforeach
                </x-slot:row>
            </x-table.data-table>
        </div>
            <div class="mt-3">
                {{ $activityLogs->links('pagination::tailwind') }}
            </div>
    </div>
@endsection