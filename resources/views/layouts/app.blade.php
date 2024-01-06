<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        <!-- wireUI -->
        <wireui:scripts />

        @filamentStyles

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-notifications z-index="z-50" />
        <x-dialog z-index="z-50" blur="md" align="center" />

        <x-banner />
        <div class="container mx-auto min-h-screen bg-white">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @filamentScripts

        @stack('modals')

        @livewire('livewire-ui-modal')

        @livewireScripts

        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>
        <!-- CDN jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
            var ratonParado = null;
            var milisegundosLimite = 5000;
            $(document).on('mousemove', function() {
                clearTimeout(ratonParado);
                ratonParado = setTimeout(function() {
                    $.ajax
                    ({
                        url: "{{ route('logout') }}",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        method: 'POST',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(response)
                        {
                            window.$wireui.dialog({
                            title: 'Sesión inactiva!',
                            description: 'Su sesión fue cerrada por inactividad. Debe iniciar sesión nuevamente.',
                            icon: 'error'
                            })
                            console.log('cerro la sesion')
                        },
                        error: function(response) {
                            console.log(response)
                        }
                    });
                }, milisegundosLimite);
            });
        </script>
    </body>
</html>
