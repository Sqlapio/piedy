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

        <style type="text/css">
            footer {
                font-size: 12px;
            }
            table { page-break-inside:auto }
            tr    { page-break-inside:avoid; page-break-after:auto }


        </style>

    </head>
    <body class="font-sans antialiased">
        <div class="p-2 mx-auto">
            <div class="p-2">
                <!-- Banner -->
                <div class="flex">
                    <img style="display: block; margin-left: auto; margin-right: auto;" src="{{ asset('images/PDF-PIEDY-SOLO-BANNER.jpg') }}">
                </div>

                <!-- Numero de reporte -->
                <div class="flex justify-end">
                    <div class="flex flex-col justify-end mt-2">
                        <p class="font-bold text-[10px] text-end text-black uppercase">Reporte General Nro. RG-{{ $periodo }}</p>
                        <p class="text-[10px] text-end text-black uppercase">{{ $rango }}</p>
                    </div>
                </div>

                <!-- Titulo de Indicadores -->
                <div class="fex mt-1">
                    <p class="mt-1 mb-1 text-[10px] font-bold text-black uppercase">Totales de Nomina</p>
                </div>

                <!-- Indicadores Linea 1 -->
                <div class="grid sm:grid-cols-3 md:grid-cols-3 gap-4">
                    <!-- Total facturado Dolares -->
                    <div>
                        <div class="flex justify-between items-center p-2 bg-white border border-[#349fda] rounded-lg shadow">
                            <div class="">
                                <p class="text-[10px] font-normal text-black uppercase">Dolares</p>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2 py-1 mr-auto font-bold text-center text-[#349fda] align-baseline rounded-lg border border-[#349fda]">
                                    <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 6.5h2M11 18h2m-7-5v-2m12 2v-2M5 8h2a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1Zm0 12h2a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1Zm12 0h2a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1Zm0-12h2a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1Z"/>
                                      </svg>
                                    ${{ $total_general_dolares }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Total facturado Bolibares -->
                    <div>
                        <div class="flex justify-between items-center p-2 bg-white border border-[#349fda] rounded-lg shadow">
                            <div class="">
                                <p class="text-[10px] font-normal text-black uppercase">Bolivares</p>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2 py-1 mr-auto font-bold text-center text-[#349fda] align-baseline rounded-lg border border-[#349fda]">
                                    <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 0 0-2 2v4m5-6h8M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m0 0h3a2 2 0 0 1 2 2v4m0 0v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6m18 0s-4 2-9 2-9-2-9-2m9-2h.01"/>
                                      </svg>
                                    Bs. {{ $total_general_bolivares }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Total General en Dolares -->
                    <div>
                        <div class="flex justify-between items-center max-w-sm p-2 bg-white border border-[#349fda] rounded-lg shadow">
                            <div class="">
                                <p class="text-[10px] font-normal text-black uppercase">Total General($)</p>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2 py-1 mr-auto font-bold text-center text-[#349fda] align-baseline rounded-lg border border-[#349fda]">
                                    <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16M8 14h8m-4-7V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
                                      </svg>

                                   ${{ $nomina_general_dolares }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Titutlo -->
                <div class="flex mt-3">
                    <p class="mt-3 mb-1 text-[10px] font-bold text-black uppercase">Detalle General de Nomina</p>
                </div>

                <!-- Tasa Bcv -->
                <div class="flex">
                    <p class="mb-1 text-[10px] font-bold text-black uppercase">Tasa BCV: Bs. {{ $tasa_bcv }}</p>
                </div>

                <!-- Tabla -->
                <div class="relative overflow-x-auto shadow-md rounded-lg border">
                    <table class="w-full text-[10px] text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class=" text-[10px] text-black uppercase bg-gray-300">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-[100px]">
                                    Empleado
                                </th>
                                <th scope="col" class="px-6 py-3 w-[80px]">
                                    Servicios
                                </th>
                                <th scope="col" class="px-6 py-3 w-[80px]">
                                    Total($)
                                </th>
                                <th scope="col" class="px-6 py-3 w-[80px]">
                                    Total(Bs.)
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nomina as $item)
                            <tr class="odd:bg-white even:bg-gray-200">
                                <th scope="row" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $item->name }}
                                </th>
                                <td class="px-6 py-2 w-[80px]">
                                    {{ $item->total_servicios }}
                                </td>
                                <td class="px-6 py-2 w-[80px]">
                                    {{ $item->total_dolares }}
                                </td>
                                <td class="px-6 py-2 w-[80px]">
                                    {{ $item->total_bolivares }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tabla Gerente -->
                <div class="relative overflow-x-auto shadow-md rounded-lg border mt-3">
                    <table class="w-full text-[10px] text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class=" text-[8px] text-black uppercase bg-gray-300">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-[50px]">
                                    Gerente
                                </th>
                                <th scope="col" class="px-6 py-3 w-[80px]">
                                    Servicios con Comisión
                                </th>
                                <th scope="col" class="px-6 py-3 w-[80px]">
                                    Comision($)
                                </th>
                                <th scope="col" class="px-6 py-3 w-[80px]">
                                    Comision Pro($)
                                </th>
                                <th scope="col" class="px-6 py-3 w-[80px]">
                                    Total($)
                                </th>
                                <th scope="col" class="px-6 py-3 w-[80px]">
                                    Total(Bs.)
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nomina_encargados as $item)
                            <tr class="odd:bg-white even:bg-gray-200">
                                <th scope="row" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $item->name }}
                                </th>
                                <td class="px-6 py-2 w-[80px]">
                                    {{ $item->total_servicios }}
                                </td>
                                <td class="px-6 py-2 w-[80px]">
                                    {{ $item->total_comision_dolares }}
                                </td>
                                <td class="px-6 py-2 w-[80px]">
                                    {{ $item->total_comision_venprod  }}
                                </td>
                                <td class="px-6 py-2 w-[80px]">
                                    {{ $item->total_dolares }}
                                </td>
                                <td class="px-6 py-2 w-[80px]">
                                    {{ $item->total_bolivares }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
