<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('images/logo-nobg.png') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Sekar Satria Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F1F1F1]">
    @if (auth()->user()->role == 'superadmin')
        <x-sidebar />
    @else
        <x-admin-sidebar />
    @endif
    <x-header username="{{ Auth::user()->username }}" :page="View::yieldContent('title')" />

    <div class="main-section w-[calc(100%-256px)] ml-64 p-9 mt-10 transition-all duration-500">
        @yield('content')
    </div>

    <x-notify::notify />
    @notifyJs
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>

</html>