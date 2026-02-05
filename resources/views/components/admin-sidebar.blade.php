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

    <form class="w-full shrink-0" action="#">
        <select data-role="{{ Auth::user()->role }}"
            class="select-business bg-[#000C66] text-white text-md font-bold block w-full p-3 border-0 focus:outline-none focus:ring-0">
            @foreach ($businesses as $business)
                <option value="{{ $business->slug }}" {{ session('current_business') === $business->slug ? 'selected' : '' }}>
                    {{ ucwords($business->name) }}
                </option>
            @endforeach
        </select>
    </form>

    <div
        class="flex-1 m-4 mt-6 overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-[#003B73] [&::-webkit-scrollbar-thumb]:bg-[#050A30] scroll-smooth">
        <ul>
            <li class="mb-2 mt-1 group sidebar-button {{ request()->is('admin/*/dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard', ['businesses' => $businessSlug]) }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-feathericon-home class="w-6 h-6" />
                    <span class="text-md">Dashboard Admin</span>
                </a>
            </li>
            <li class="mb-2 group sidebar-button {{ Route::is('admin.catat-pemasukan.*') ? 'active' : '' }}">
                <a href="{{ route('admin.catat-pemasukan.index', ['businesses' => $businessSlug]) }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-tabler-moneybag-move class="w-6 h-6" />
                    <span class="text-md">Catat Pemasukan</span>
                </a>
            </li>
            <li class="mb-2 group sidebar-button {{ Route::is('admin.catat-pengeluaran.*') ? 'active' : '' }}">
                <a href="{{ route('admin.catat-pengeluaran.index', ['businesses' => $businessSlug]) }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-tabler-moneybag-move-back class="w-6 h-6" />
                    <span class="text-md">Catat Pengeluaran</span>
                </a>
            </li>
            <li class="mb-2 group sidebar-button {{ Route::is('admin.utangpiutang.*') ? 'active' : '' }}">
                <a href="{{ route('admin.utangpiutang.index', ['businesses' => $businessSlug]) }}"
                    class="flex items-center rounded-md gap-3 py-2 px-4 text-white hover:bg-gray-700 group-[.active]:bg-gray-700">
                    <x-tabler-notes class="w-6 h-6" />
                    <span class="text-md">Utang dan Piutang</span>
                </a>
            </li>
            <li class=" group sidebar-button {{ Route::is('admin.report.*') ? 'active' : '' }}">
                <a href="{{ route('admin.report.index', ['businesses' => $businessSlug]) }}"
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