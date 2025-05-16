<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(app()->getLocale() === 'ar')
dir="rtl"
@endif
class="scroll-smooth"
>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate(app()->isProduction()) !!}

    <!-- Fonts -->
    <link href="" data-font="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @isset($style)
    {{ $style }}
    @endisset
    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-['Rubik'] antialiased">
    <x-banner />

    <div class="min-h-screen-without-header bg-gray-100">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif
        @if (session('success'))
        <x-alert type="success">
            {{ session('success') }}
        </x-alert>
        @elseif (session('error'))
        <x-alert type="error">
            {{ session('error') }}
        </x-alert>
        @endif
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    @include('layouts.footer')
    @stack('modals')
    @livewireScripts
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        window.addEventListener('load', () => {
            const fontLink = document.querySelector('[data-font]');
            fontLink.setAttribute('href', fontLink.getAttribute('data-font') ?? '');
        });
        document.addEventListener('toastify', (e) => {
            console.log(e);
            Toastify({
                text: e.detail.message,
                type: e.detail.type,
                duration: 3000,
                newWindow: true,
                close: true,
                stopOnFocus: true,
            }).showToast();
        });
    </script>
    @isset($script)
    {{ $script }}
    @endisset
</body>

</html>
