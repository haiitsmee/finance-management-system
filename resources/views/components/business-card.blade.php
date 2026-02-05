<div class="flex flex-col p-4 w-full h-auto border border-{#050A30] rounded-lg mt-5">
    <div class="flex flex-col mb-5">
        <div class="flex items-center justify-between">
            <h1 class="font-bold">Divisi {{ $businessName }}</h1>
            <a href="{{ route('superadmin.manajemen-divisi.show', ['manajemen_divisi' => $businessId]) }}" class="hover:text-gray-400">
                <x-tabler-pencil class="w-5 h-5" />
            </a>
        </div>
        <span>ID Divisi : {{ $businessId }}</span>
    </div>
    <hr class="border-t-2 border-dashed border-gray-400">
    <div class="flex flex-col mt-5">
        <h1 class="font-bold">Admin</h1>
        <div class="flex gap-3">
            <div class="flex gap-2 items-center">
                @foreach ($businessAdmins as $user)
                    <img src="{{ asset('images/photo-profile.png') }}" alt="Photo Profile"
                        class="w-4 h-4 rounded-full object-cover">
                    <span>{{ ucwords($user->username) }}</span>
                @endforeach
            </div>
        </div>
    </div>
</div>