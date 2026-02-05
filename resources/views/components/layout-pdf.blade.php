{{-- resources/views/components/layout-pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Document')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Kop Surat Styles */
        .letterhead {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #000;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .business-division {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #555;
            text-transform: uppercase;
        }

        .business-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 12px;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .address {
            font-size: 11px;
            line-height: 1.3;
            margin-bottom: 8px;
        }

        .address-indent {
            margin-left: 60px;
            text-align: left;
        }

        .contact {
            font-size: 11px;
        }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Kop Surat --}}
    @if(isset($letterhead) && $letterhead)
        @include('components.letterhead-pdf', [
            'division' => $letterhead['division'],
            'businessName' => $letterhead['name']
        ])
    @endif

    {{-- Content --}}
    @yield('content')
</body>
</html>