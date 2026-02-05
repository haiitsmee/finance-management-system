@php
    $businessSlug = session('current_business');

@endphp

<div class="sidebar-menu fixed left-0 top-0 h-full w-64 bg-[#050A30] transition-all duration-500 flex flex-col">
    <div class="flex items-center p-5">
        <div class="">
            <img src="{{ asset('images/logo-nobg.png') }}" alt="Sekar Satria Logo" class="w-8 h-8 bg-white rounded-full">
        </div>
        <div class="flex flex-col ml-3">
            <span class="text-lg font-bold text-white">SEKAR SATRIA</span>
            <span class="text-sm font-bold text-white">Management</span>
        </div>
    </div>

    <div class="w-full shrink-0">
        <select data-role="{{ Auth::user()->role }}"
            class="select-business bg-[#000C66] text-white text-md font-bold block w-full p-3 border-0 focus:outline-none focus:ring-0">
            @foreach ($businesses as $business)
                @if ($business->is_visible)
                    <option value="{{ $business->slug }}" {{ session('current_business') === $business->slug ? 'selected' : '' }}>
                        {{ ucwords($business->name) }}
                    </option>
                @endif
            @endforeach
        </select>

    </div>

    <div
        class="flex-1 m-4 mt-6 overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-[#003B73] [&::-webkit-scrollbar-thumb]:bg-[#050A30] scroll-smooth">
        <ul>
            <li
                class="mb-1 pb-3 border-b-2 border-b-[rgb(0,12,102)] group sidebar-button {{ Route::is('superadmin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('superadmin.dashboard') }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-feathericon-star class="w-6 h-6" />
                    <span class="text-md">Super Admin</span>
                </a>
            </li>
            <li class="mb-1 group sidebar-button {{ Route::is('superadmin.laporan-keuangan-all.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.laporan-keuangan-all.index') }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-tabler-report-money class="w-6 h-6" />
                    <span class="text-md">Laporan Keuangan</span>
                </a>
            </li>
            <li class="mb-1 {{ Route::is('superadmin.manajemen-admin.*') ? 'active' : '' }} group sidebar-button">
                <a href="{{ route('superadmin.manajemen-admin.index') }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-tabler-users-group class="w-6 h-6" />
                    <span class="text-md">Manajemen Admin</span>
                </a>
            </li>
            <li class="mb-1 group sidebar-button {{ Route::is('superadmin.manajemen-divisi.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.manajemen-divisi.index') }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-tabler-building class="w-6 h-6" />
                    <span class="text-md">Manajemen Divisi</span>
                </a>
            </li>
            <li class="mb-1 {{ Route::is('superadmin.manajemen-kategori.*') ? 'active' : '' }} group sidebar-button">
                <a href="{{ route('superadmin.manajemen-kategori.index') }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-tabler-category class="w-6 h-6" />
                    <span class="text-md">Manajemen Kategori</span>
                </a>
            </li>
            <li class="mb-1 group sidebar-button {{ Route::is('superadmin.csr.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.csr.index') }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-tabler-moneybag-heart class="w-6 h-6" />
                    <span class="text-md">CSR</span>
                </a>
            </li>
            <li class="mb-1 group sidebar-button mt-3 {{ Route::is('superadmin.log-aktifitas.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.log-aktifitas.index') }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-tabler-notebook class="w-6 h-6" />
                    <span class="text-md">Log Aktifitas</span>
                </a>
            </li>
            <li class="mb-1 group sidebar-button mt-3 {{ Route::is('superadmin.catat-pengeluaran.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.catat-pengeluaran-pusat.index') }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-tabler-moneybag-move-back class="w-6 h-6" />
                    <span class="text-md">Catat Pengeluaran</span>
                </a>
            </li>

            <div class="mt-10 text-md text-white font-bold">Dashboard Divisi</div>
            <li class="mb-1 mt-3 group sidebar-button {{ request()->is('superadmin/*/dashboard') ? 'active' : '' }}">
                <a href="{{ route('superadmin.dashboard.index', ['businesses' => $businessSlug]) }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-feathericon-home class="w-6 h-6" />
                    <span class="text-md">Dashboard Admin</span>
                </a>
            </li>
            <li class="mb-1 group sidebar-button {{ Route::is('superadmin.catat-pemasukan.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.catat-pemasukan.index', ['businesses' => $businessSlug]) }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-tabler-moneybag-move class="w-6 h-6" />
                    <span class="text-md">Catat Pemasukan</span>
                </a>
            </li>
            <li class="mb-1 group sidebar-button {{ Route::is('superadmin.catat-pengeluaran.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.catat-pengeluaran.index', ['businesses' => $businessSlug]) }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-tabler-moneybag-move-back class="w-6 h-6" />
                    <span class="text-md">Catat Pengeluaran</span>
                </a>
            </li>
            <li class="mb-1 group sidebar-button {{ Route::is('superadmin.utangpiutang.*') ? 'active' : '' }}"">
                <a href="{{ route('superadmin.utangpiutang.index', ['businesses' => $businessSlug]) }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-tabler-notes class="w-6 h-6" />
                    <span class="text-md">Utang dan Piutang</span>
                </a>
            </li>
            <li class="mb-10 group sidebar-button {{ Route::is('superadmin.report.*') ? 'active' : '' }}"">
                <a href="{{ route('superadmin.report.index', ['businesses' => $businessSlug]) }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-tabler-report-analytics class="w-6 h-6" />
                    <span class="text-md">Laporan Keuangan</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const businessOptions = document.querySelector('.select-business');

        if (businessOptions) {
            businessOptions.addEventListener('change', function () {
                const role = this.dataset.role;
                const businessSlug = this.value;

                if (businessSlug) {
                    window.location.href = `/${role}/${businessSlug}/dashboard`;
                }
            });
        }
    });
</script>