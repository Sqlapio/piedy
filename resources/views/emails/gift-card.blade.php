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
        @notifyCss
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
<body>
    <img style="display: block; margin-left: auto; margin-right: auto; width: 600px; height: auto;" src="{{ asset('images/banner_correo.jpg') }}">
    <div style="margin: auto; width: 600px; padding: 10px;">
        <div class="w-full">
            <div class="w-full h-auto m-auto bg-red-100 rounded-xl relative text-white shadow-2xl transition-transform transform hover:scale-110 ">

                <img class="object-cover w-full h-full rounded-xl" src="{{ asset('images/gift-card1.png') }}"/>

                <div class="w-full -mb-10 px-8 absolute top-20">
                    <div class="flex justify-between">
                        <div class="">
                            <p class="font-light text-black text-xs">
                                Nombre
                            </p>
                            <p class="font-medium tracking-widest text-black">
                                Guistavo Camacxho
                            </p>
                        </div>
                        {{-- <img class="w-1/4 h-auto" src="{{ asset('images/logo.png') }}"/> --}}

                    </div>
                    <div class="pt-1">
                        <p class="font-light text-black text-xs py-1">
                            Código de Tarjeta
                        </p>

                        <p class="font-medium tracking-more-wider text-black">
                            <img class="w-full h-full" src="{{ asset('/storage/barcodes/1613_barcode.jpg') }}"/>
                        </p>
                    </div>
                    <div class="pt-6 pr-6">
                        <div class="flex justify-between">
                            <div class="">
                                <p class="font-light text-xs text-black">
                                    Valida
                                </p>
                                <p class="font-bold tracking-more-wider text-sm text-black">
                                    23/23
                                </p>
                            </div>
                            <div class="">
                                <p class="font-light text-xs text-xs text-black">
                                    Expira
                                </p>
                                <p class="font-bold tracking-more-wider text-sm text-black">
                                    11/24
                                </p>
                            </div>

                            <div class="">
                                <p class="font-light text-xs text-black">
                                    PGC
                                </p>
                                <p wire:model.live='pgc' class="font-bold tracking-more-wider text-sm text-black">
                                    34567
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <p style="text-align: justify; margin-left: 20px;">
            Esta notificación confirma que el proceso de facturación asociado a su trabajo ha sido completado correctamente. <br>
            Agradecemos su esfuerzo continuo y la dedicación con la que lleva a cabo cada servicio, lo cual es fundamental para nuestro compromiso con la excelencia. <br>
            Si tiene alguna pregunta o necesita asistencia adicional, no dude en ponerse en contacto con nosotros.
        </p>
    </div>

</body>
</html>
