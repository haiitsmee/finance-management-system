@extends("components.layout")
@section('title', 'Utang Piutang')

@section('content')
    <div class="flex flex-col justify-end">
        <span>
            <h1 class="text-2xl text-black">Utang Piutang Divisi: <strong>{{ session('current_business_name') }}</strong>
            </h1>
        </span>
        <div class="flex flex-col justify-end">
            <div class="flex flex-col items-end">
                <x-button-add
                    direct="{{ route(auth()->user()->role . '.utangpiutang.create', parameters: ['businesses' => session('current_business')]) }}"
                    value="Tambah Utang" />
                <form action="{{ route(auth()->user()->role . '.utangpiutang.index', session('current_business')) }}"
                    method="GET" class="py-2">
                    <select name="sort_by" id="sort_by" onchange="this.form.submit()"
                        class="border rounded px-2 py-2 bg-white   ">
                        <option value="">-- Pilih --</option>
                        <option value="date" {{ request('sort_by') == 'date' ? 'selected' : '' }}>Tanggal Transaksi</option>
                        <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Status (Belum Terbayar
                            Dulu)</option>
                    </select>
                </form>
            </div>
            <div class="">
                <h1 class="text-lg font-md">Tabel Utang</h1>
                <x-table.data-table>
                    <x-slot:header>
                        <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Tanggal Transaksi
                        </th>
                        <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Pemasok</th>
                        <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Kategori Utang</th>
                        <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Jumlah Utang</th>
                        <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Status Pembayaran
                        </th>
                        <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Detail</th>
                        <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Aksi</th>
                    </x-slot:header>
                    <x-slot:row>
                        @foreach ($utangData as $utang)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $utang->date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ ucwords($utang->supplier) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $utang->category }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">@currency($utang->amount)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{  $utang->due_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($utang->is_paid)
                                        <span class="px-4 py-1 rounded-2xl text-xs bg-green-300">Sudah Dibayar</span>
                                    @else
                                        <span class="px-4 py-1 rounded-2xl text-xs bg-red-300">Belum Dibayar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <x-table.button-detail
                                        redirect="{{ route(auth()->user()->role . '.utangpiutang.show', ['businesses' => session('current_business'), $utang->id]) }}" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex gap-3 items-center">
                                        <x-table.button-table :submit="false"
                                            redirect="{{ route(auth()->user()->role . '.utangpiutang.edit', ['businesses' => session('current_business'), 'id' => $utang->id]) }}"
                                            color="" value="Edit" type="edit" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot:row>
                </x-table.data-table>
                <div class="mt-3">
                    {{ $utangData->links('pagination::tailwind') }}
                </div>
            </div>
            <div class="mt-10">
                <h1 class="text-lg font-md">Tabel Piutang</h1>

                <div class="space-y-8">

                    <x-table.data-table>
                        <x-slot:header>
                            <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Tanggal
                                Transaksi
                            </th>
                            <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">ID Transaksi
                            </th>
                            <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Kategori Piutang
                            </th>
                            <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Jumlah Utang
                            </th>
                            <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Detail</th>
                            <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Aksi</th>
                        </x-slot:header>
                        <x-slot:row>
                            @foreach ($piutangData as $piutang)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $piutang->created_at }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ ucwords($piutang->id) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $piutang->transactionCategory->title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">@currency($piutang->total)</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <x-table.button-detail
                                            redirect="{{ route('admin.utangpiutang.show', ['businesses' => session('current_business'), $utang->id]) }}" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex gap-3 items-center">
                                            <x-table.button-table :submit="false"
                                                redirect="{{ route('admin.utangpiutang.edit', ['businesses' => session('current_business'), 'id' => $utang->id]) }}"
                                                color="" value="Edit" type="edit" />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </x-slot:row>
                    </x-table.data-table>
                    <div class="mt-3">
                        {{ $piutangData->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection