@extends('components.layout')

@section('title', 'Pengeluaran Pusat')

@section('content')
    <div class="flex flex-col">
        <h1 class="text-2xl text-black">Catat Pengeluaran: <strong>Pusat</strong></h1>

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

        <div class="flex min-w-full justify-start gap-8 px-4 py-5 md:flex-col flex-row">
            <form action="{{ route(auth()->user()->role . '.catat-pengeluaran-pusat.store', ['businesses' => 'Pusat']) }}" method="POST" enctype="multipart/form-data">
            @csrf    
            <div class="flex min-w-full justify-start gap-3 lg:gap-8 px-4 py-5 md:flex-row flex-col">
                    <div class="flex flex-col gap-3">
                        <x-form.input for="tanggal_transaksi" label="Tanggal Transaksi" placeholder="" :readonly="false"
                            :required="true" type="date" :value="date('d F Y')" width="w-70" />
                        <x-form.input for="produk" label="Nama Produk/Jasa" placeholder="Nama produk/jasa" :required="true"
                            type="text" value="" :readonly="false" width="w-70" />
                        <x-form.select for="status" label="Status Pembayaran" width="w-70">
                            <x-slot:option>p
                                <option value="" selected disabled>Pilih Status</option> 
                                <option value="lunas">Lunas</option>
                                <option value="bon">Bon</option>
                            </x-slot:option>
                        </x-form.select>
                        <x-form.select for="transaction_category_id" label="Kategori Transaksi" width="w-70" >
                            <x-slot:option>
                                <option value="" selected disabled>Pilih Kategori</option>
                                @foreach ($transactionCategories as $category)
                                    <option value="{{ $category->id }}">{{ ucwords($category->title) }}</option>
                                @endforeach
                            </x-slot:option>
                        </x-form.select>
                        
                    </div>
                    <div class="flex flex-col gap-3">
                        <x-form.input for="business" label="Divisi Terkait" placeholder="Divisi Terkait" :required="true" :readonly="true"
                            type="text" :value="'Pusat'" width="w-70" />
                        <x-form.input for="jumlah_unit" label="Jumlah Unit" placeholder="Jumlah unit" :required="true"
                            :readonly="false" type="number" value="" width="w-70" />
                        <x-form.input for="harga" label="Harga Unit" placeholder="Harga unit" :required="true"
                            :readonly="false" type="text" value="" width="w-70" />
                        <x-form.input for="total" label="Total" placeholder="Total" :required="true" :readonly="true"
                        type="text" value="" width="w-70" />
                    </div>
                    <div class="flex flex-col gap-5">
                        <x-forms.textarea for="deskripsi" label="Keterangan (opsional)" rows="4" cols="30"
                            placeholder="Keterangan" />
                        <x-forms.file-input for="gambar" label="Bukti Pengeluaran" :required="true" width="w-70" note="Hanya mendukung format JPG, JPEG, PNG" />
                    </div>
                </div>
                <div class="flex gap-2">
                            <x-forms.button type="submit" value="Submit" class="w-35 mt-7 bg-[#050A30] hover:bg-gray-200 hover:text-[#050A30] text-white border border-[#050A30]" />
                            <x-forms.button type="button" value="Cancel" class="cancel-button w-35 mt-7 bg-[#F1F1F1] hover:bg-[#050A30] hover:text-gray-200 text-[#050A30] border border-[#050A30]" />
                        </div>
            </form>
        </div>

        <div class="mt-10">
            <div class="flex self-end gap-3 justify-end">
                <form id="search-form" action="{{ route(auth()->user()->role . '.catat-pengeluaran-pusat.index', ['businesses' => 'Pusat']) }}" method="GET" class="flex items-center gap-1">                    
                    <input type="text" name="search" value="{{ request('search') }}"  class="w-full px-4 py-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-300" placeholder="Cari transaksi...">
                    <input id="tanggal" type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full px-4 py-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-300">
                    <button type="submit" class="w-10 h-10 flex items-center justify-center text-white text-center bg-[#050A30] rounded-md hover:bg-gray-200 hover:text-[#050A30] transition-all duration-500"><x-feathericon-search class="" /></button>
                </form>
            </div>
            <x-table.data-table>
                <x-slot:header>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Produk / Jasa</th>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Jumlah Unit</th>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Harga per-unit</th>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Total Pengeluaran</th>
                    <th class="px-6 py-3 text-sm font-medium text-custom-light-gray tracking-wider">Status</th>
                </x-slot:header>
                <x-slot:row>
                    @foreach ($transactionExpenses as $income)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $income->transaction_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ ucwords($income->product) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $income->product_quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">@currency($income->price)</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">@currency($income->total)</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if ($income->status == 'lunas')
                                    <span class="px-4 py-1 rounded-2xl text-xs bg-green-300">{{ ucwords($income->status) }}</span>
                                @else
                                    <span class="px-4 py-1 rounded-2xl text-xs bg-red-300">{{ ucwords($income->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <x-table.button-detail redirect="{{ route(auth()->user()->role . '.catat-pengeluaran-pusat.show', ['businesses' => "Pusat", $income->id ]) }}" />
                            </td>
                        </tr>
                    @endforeach
                </x-slot:row>
            </x-table.data-table>
            <div class="mt-3">
                {{ $transactionExpenses->links('pagination::tailwind') }}
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jumlahInput = document.getElementById('jumlah_unit');
            const hargaInput = document.getElementById('harga');
            const totalInput = document.getElementById('total');

            function formatRupiah(angka) {
                return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function updateTotal() {
                let jumlah = parseInt(jumlahInput.value) || 0;
                let harga = parseInt(hargaInput.value.replace(/\D/g, '')) || 0;
                let total = jumlah * harga;
                totalInput.value = total > 0 ? 'Rp ' + formatRupiah(total.toString()) : 'Rp 0';
            }

            // Format input harga ke Rupiah
            hargaInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                e.target.value = value ? 'Rp ' + formatRupiah(value) : '';
                updateTotal();
            });

            // Update total saat jumlah berubah
            jumlahInput.addEventListener('input', updateTotal);
        });

        document.getElementById('tanggal').addEventListener('change', function() {
            const tanggal = this.value;
            if (tanggal) {
                window.location.href = "{{ route(auth()->user()->role . '.catat-pengeluaran-pusat.index', ['businesses' => "Pusat"]) }}" + "?tanggal=" + tanggal;
            } else {
                    window.location.href = "{{ route(auth()->user()->role . '.catat-pengeluaran-pusat.index', ['businesses' => "Pusat"]) }}";
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const jumlahInput = document.getElementById('jumlah_unit');
            const hargaInput = document.getElementById('harga');
            const totalInput = document.getElementById('total');
            const cancelBtn = document.querySelector('.cancel-button');

            function formatRupiah(angka) {
                return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function updateTotal() {
                let jumlah = parseInt(jumlahInput.value) || 0;
                let harga = parseInt(hargaInput.value.replace(/\D/g, '')) || 0;
                let total = jumlah * harga;
                totalInput.value = total > 0 ? 'Rp ' + formatRupiah(total.toString()) : 'Rp 0';
            }

            hargaInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                e.target.value = value ? 'Rp ' + formatRupiah(value) : '';
                updateTotal();
            });

            jumlahInput.addEventListener('input', updateTotal);

            // Tombol Cancel untuk reset semua input
            cancelBtn.addEventListener('click', function () {
                document.querySelector('form').reset(); // reset semua input form
                totalInput.value = ''; // pastikan total ikut kosong
            });
        });
    </script>
@endsection