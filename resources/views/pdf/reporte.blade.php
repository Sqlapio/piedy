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
        <div class="px-4 mx-auto">
            <div class="px-4">
                <!-- Banner -->
                <div class="flex">
                    <img style="display: block; margin-left: auto; margin-right: auto;" src="{{ asset('images/piedy_pdf_2.jpg') }}">
                </div>

                <!-- Numero de reporte -->
                <div class="flex justify-end">
                    <div class="flex flex-col justify-end mt-5">
                        <p class="font-bold text-[8px] text-end text-black uppercase">Reporte Nro.{{ $nro_reporte }}</p>
                        <p class="text-[8px] text-end text-black uppercase">{{ $rango }}</p>
                    </div>
                </div>

                <!-- Empleado -->
                <div class="flex flex-col">
                    <p class="mt-5 text-[8px] font-bold text-black uppercase">Empleado: {{ $nombre }}</p>
                    <p class="mb-4 text-[8px] font-bold text-black uppercase">C.I.: {{ $cedula }}</p>
                </div>

                <!-- Indicadores -->
                <div class="grid md:grid-cols-2 sm:gap-2 md:gap-2">
                    <!-- Total de servicios -->
                    <div>
                        <div class="flex justify-between items-center max-w-sm px-2 py-1 bg-white border border-[#349fda] rounded-lg shadow">
                            <div class="">
                                <p class="text-[8px] font-normal text-black uppercase">Total de Servicios</p>
                            </div>
                            <div>
                                <span class="text-[8px] inline-flex items-center px-2 py-1 mr-auto font-bold text-center text-[#349fda] align-baseline rounded-lg border border-[#349fda]">
                                    <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 6.5h2M11 18h2m-7-5v-2m12 2v-2M5 8h2a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1Zm0 12h2a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1Zm12 0h2a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1Zm0-12h2a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1Z"/>
                                      </svg>
                                    {{ $total_servicios }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Productos asigandos -->
                    <div>
                        <div class="flex justify-between items-center max-w-sm px-2 py-1 bg-white border border-[#349fda] rounded-lg shadow">
                            <div class="">
                                <p class="text-[8px] font-normal text-black uppercase">Eficiencia</p>
                            </div>
                            <div>
                                <span class="text-[8px] inline-flex items-center px-2 py-1 mr-auto font-bold text-center text-[#349fda] align-baseline rounded-lg border border-[#349fda]">
                                    <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 0 0-2 2v4m5-6h8M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m0 0h3a2 2 0 0 1 2 2v4m0 0v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6m18 0s-4 2-9 2-9-2-9-2m9-2h.01"/>
                                      </svg>
                                    0
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- promedio de duracion -->
                    <div>
                        <div class="flex justify-between items-center max-w-sm px-2 py-1 bg-white border border-[#349fda] rounded-lg shadow">
                            <div class="">
                                <p class="text-[8px] font-normal text-black uppercase">Duracion de servicios</p>
                            </div>
                            <div>
                                <span class="text-[8px] inline-flex items-center px-2 py-1 mr-auto font-bold text-center text-[#349fda] align-baseline rounded-lg border border-[#349fda]">
                                    <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10.827 5.465-.435-2.324m.435 2.324a5.338 5.338 0 0 1 6.033 4.333l.331 1.769c.44 2.345 2.383 2.588 2.6 3.761.11.586.22 1.171-.31 1.271l-12.7 2.377c-.529.099-.639-.488-.749-1.074C5.813 16.73 7.538 15.8 7.1 13.455c-.219-1.169.218 1.162-.33-1.769a5.338 5.338 0 0 1 4.058-6.221Zm-7.046 4.41c.143-1.877.822-3.461 2.086-4.856m2.646 13.633a3.472 3.472 0 0 0 6.728-.777l.09-.5-6.818 1.277Z"/>
                                      </svg>
                                    {{ $pro_dura_servicios }}(min)
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- dias trabajados -->
                    <div>
                        <div class="flex justify-between items-center max-w-sm px-2 py-1 bg-white border border-[#349fda] rounded-lg shadow">
                            <div class="">
                                <p class="text-[8px] font-normal text-black uppercase">dias laborales</p>
                            </div>
                            <div>
                                <span class="text-[8px] inline-flex items-center px-2 py-1 mr-auto font-bold text-center text-[#349fda] align-baseline rounded-lg border border-[#349fda]">
                                    <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16M8 14h8m-4-7V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
                                      </svg>
                                    15
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Totales a pagar -->
                <div class="grid md:grid-cols-2 sm:gap-2 md:gap-2 mt-3">
                    <!-- Dolares -->
                    <div>
                        <div class="flex justify-between items-center max-w-sm p-1 bg-white border border-green-500 rounded-lg shadow">
                            <div class="">
                                <p class="text-[8px] font-extrabold text-black uppercase">Total Dolares</p>
                            </div>
                            <div>
                                <span class="text-[8px] inline-flex items-center px-2 py-1 mr-auto font-extrabold text-center text-green-500 align-baseline rounded-lg border border-green-500">
                                    <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.6 16.733c.234.269.548.456.895.534a1.4 1.4 0 0 0 1.75-.762c.172-.615-.446-1.287-1.242-1.481-.796-.194-1.41-.861-1.241-1.481a1.4 1.4 0 0 1 1.75-.762c.343.077.654.26.888.524m-1.358 4.017v.617m0-5.939v.725M4 15v4m3-6v6M6 8.5 10.5 5 14 7.5 18 4m0 0h-3.5M18 4v3m2 8a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z"/>
                                      </svg>
                                    ${{ $total_dolares }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Bolivares -->
                    <div>
                        <div class="flex justify-between items-center max-w-sm p-1 bg-white border border-green-500 rounded-lg shadow">
                            <div class="">
                                <p class="text-[8px] font-extrabold text-black uppercase">Total Bolivares</p>
                            </div>
                            <div>
                                <span class="text-[8px] inline-flex items-center px-2 py-1 mr-auto font-extrabold text-center text-green-500 align-baseline rounded-lg border border-green-500">
                                    <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.6 16.733c.234.269.548.456.895.534a1.4 1.4 0 0 0 1.75-.762c.172-.615-.446-1.287-1.242-1.481-.796-.194-1.41-.861-1.241-1.481a1.4 1.4 0 0 1 1.75-.762c.343.077.654.26.888.524m-1.358 4.017v.617m0-5.939v.725M4 15v4m3-6v6M6 8.5 10.5 5 14 7.5 18 4m0 0h-3.5M18 4v3m2 8a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z"/>
                                      </svg>
                                    Bs. {{ $total_bolivares }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Tabla de servicios -->
                <div class="flex mt-5">
                    <p class="mt-5 mb-4 text-[8px] font-bold text-black uppercase">Detalle de servicios</p>
                </div>

                <div class="relative overflow-x-auto shadow-md rounded-lg border">
                    <table class="table-fixed w-full text-[8px] text-left text-gray-500 dark:text-gray-400">
                        <thead class=" text-[8px] text-black uppercase bg-gray-300">
                            <tr>
                                <th scope="col" class="px-6 py-3 ">
                                    cliente
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tiempo Trabajo
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Comision($)
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Comision($)
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($servicios as $servicio)
                            <tr class="odd:bg-white even:bg-gray-200">
                                <th class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white w-[100px] truncate">
                                    {{ $servicio->cliente }}
                                </th>
                                <td class="px-6 py-2 text-[8px]">
                                    <div class="flex flex-col">
                                        <div>
                                            {{ $servicio->duracion }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-2 text-[8px]">
                                    <div class="flex flex-col">
                                        <div>
                                            ${{ $servicio->comision_dolares }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-2 text-[8px]">
                                    <div class="flex flex-col">
                                        <div>
                                            Bs.{{ $servicio->comision_bolivares }}
                                        </div>
                                    </div>
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
