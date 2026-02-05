<div
    class="header z-50 flex items-center justify-between fixed top-0 w-[calc(100%-256px)] h-14 bg-[#050A30] ml-64 transition-all duration-500">
    <div class="flex items-center">
        <x-heroicon-o-bars-3 class="sidebar-toggle w-6 h-6 ml-6 text-white hover:bg-gray-600 hover:rounded" />
        <h1 class="ml-5 text-white text-xl text-center">{{ $page }}</h1>
    </div>
    <div class="profile-click flex items-center mr-4 cursor-pointer">
        <img src="{{ asset('images/photo-profile.png') }}" alt="Photo Profile"
            class="w-10 h-10 roundped-full object-cover">
        <span class="text-lg text-white ml-3">{{ ucwords($username) }}</span>
    </div>
    <form action="{{ route('logout') }}" method="POST" class="absolute top-full right-0 mt-2 inline-block">
        @csrf
        <button
            type="submit"
            class="popover flex justify-between items-center p-4 text-sm break-words hover:bg-gray-200 hover:rounded-lg hover:text-[#050A30] absolute opacity-0 -translate-y-2 top-full right-0 mr-3 mt-2 bg-[#050A30] rounded-lg shadow-lg w-max text-white shadow-blue-gray-500/10  transition-opacity duration-500">
            <span class="mr-10">Logout</span>
            <x-tabler-logout class="w-4 h-4" />
        </button>
    </form>
</div>