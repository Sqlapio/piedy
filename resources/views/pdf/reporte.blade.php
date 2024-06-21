<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" sizes="256x256" href="{{ asset('images/favicon.ico') }}">
        <link rel="icon" sizes="180x180" href="{{ asset('images/favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}"/>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">

        <div class="container mx-auto">

            
            <div class="p-5">
                <div class="flex">
                    <img style="display: block; margin-left: auto; margin-right: auto;" src="{{ asset('images/banner_correo.jpg') }}">
                </div>
                <div class="fex">
                    <p class="mb-3 font-bold text-black uppercase">Reporte de pago</p>
                </div>
                <div class="fex">
                    <p class="mb-3 text-md text-black ">Empleado: Gustavo Camacho</p>
                </div>
                <div class="grid md:grid-cols-2 sm:gap-4 md:gap-4 mb-40">
                    <div>
                        <div class="max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <p class="text-xs font-normal text-black uppercase">Servicios</p>
                            
                        </div>
                    </div>
                    <div>
                        <div class="max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <p class="text-xs text-black uppercase">Productos Asignados</p>
                            
                        </div>
                    </div>
                    <div>
                        <div class="max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <p class="text-xs text-black uppercase">Duración promedio de servicios</p>
                            
                        </div>
                    </div>
                    <div>
                        <div class="max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <p class="text-xs text-black uppercase">Dias trabajados</p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
