<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo-nobg.png') }}" type="image/x-icon">
    <title>@yield('title') | Sekar Management</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="">
        @yield('content')
    </div>

    <x-notify::notify />
    @notifyJs
</body>

</html>