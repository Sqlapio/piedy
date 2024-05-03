<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Piedy.com</title>
    <style>
        #customers {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        #customers td, #customers th {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #7898a5;
        color: black;
        }

    </style>
</head>
<body>
    <img style="display: block; margin-left: auto; margin-right: auto; width: 600px; height: auto;" src="{{ asset('images/banner_correo.jpg') }}">
    <div style="margin: auto; width: 600px; padding: 10px;">
        {{-- <p style="text-align: justify; margin-left: 20px;">
            <h2 style="text-align: justify; margin-left: 20px;">Administrador</h2>
        </p> --}}
        <div class="w-1/2">
            <div class="w-96 h-56 m-auto bg-red-100 rounded-xl relative text-white shadow-2xl transition-transform transform hover:scale-110 ">

                {{-- <img class="relative object-cover w-full h-full rounded-xl" src="https://i.imgur.com/kGkSg1v.png"> --}}

                <div class="w-full px-8 absolute top-8">
                    <div class="flex justify-between">
                        <div class="">
                            <p class="font-light text-black text-xs">
                                Nombre
                            </p>
                            <p class="font-medium tracking-widest text-black">
                                {{ $mailData['cliente'] }}
                            </p>
                        </div>
                        <img class="w-1/4 h-auto" src="{{ asset('images/logo.png') }}"/>
                    </div>
                    <div class="pt-1">
                        <p class="font-light text-black text-xs py-1">
                            Código de Tarjeta
                        </p>

                        <p class="font-medium tracking-more-wider text-black">
                            {!! $mailData['barcode'] !!}
                        </p>
                    </div>
                    <div class="pt-6 pr-6">
                        <div class="flex justify-between">
                            <div class="">
                                <p class="font-light text-xs text-black">
                                    Valida
                                </p>
                                <p class="font-bold tracking-more-wider text-sm text-black">
                                    {{ date('m/y') }}
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
                                    {{ $mailData['pgc'] }}
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
