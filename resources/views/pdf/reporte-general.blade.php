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
                    <img style="display: block; margin-left: auto; margin-right: auto;" src="{{ asset('images/piedy_pdf_2.jpg') }}">
                </div>

                <!-- Numero de reporte -->
                <div class="flex justify-end">
                    <div class="flex flex-col justify-end mt-5">
                        <p class="font-bold text-xs text-end text-black uppercase">Reporte General Nro. RG-{{ $periodo }}</p>
                        <p class="text-xs text-end text-black uppercase">{{ $rango }}</p>
                    </div>
                </div>

                <!-- Tabla de servicios -->
                <div class="fex mt-5">
                    <p class="mt-5 mb-1 text-xs font-bold text-black uppercase">Totales de Nomina</p>
                </div>
                <!-- Indicadores -->
                <div class="grid sm:grid-cols-4 md:grid-cols-4 gap-4">
                    <!-- Tasa Bcv -->
                    <div>
                        <div class="flex justify-between items-center max-w-sm p-3 bg-white border border-[#349fda] rounded-lg shadow">
                            <div class="">
                                <p class="text-xs font-normal text-black uppercase">Tasa BCV</p>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2 py-1 mr-auto font-bold text-center text-[#349fda] align-baseline rounded-lg border border-[#349fda]">
                                    <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10.827 5.465-.435-2.324m.435 2.324a5.338 5.338 0 0 1 6.033 4.333l.331 1.769c.44 2.345 2.383 2.588 2.6 3.761.11.586.22 1.171-.31 1.271l-12.7 2.377c-.529.099-.639-.488-.749-1.074C5.813 16.73 7.538 15.8 7.1 13.455c-.219-1.169.218 1.162-.33-1.769a5.338 5.338 0 0 1 4.058-6.221Zm-7.046 4.41c.143-1.877.822-3.461 2.086-4.856m2.646 13.633a3.472 3.472 0 0 0 6.728-.777l.09-.5-6.818 1.277Z"/>
                                      </svg>

                                    Be. {{ $tasa_bcv }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center max-w-sm p-3 bg-white border border-[#349fda] rounded-lg shadow">
                            <div class="">
                                <p class="text-xs font-normal text-black uppercase">Dolares</p>
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
                    <!-- Total Dolares -->
                    <div>
                        <div class="flex justify-between items-center max-w-sm p-3 bg-white border border-[#349fda] rounded-lg shadow">
                            <div class="">
                                <p class="text-xs font-normal text-black uppercase">Bolivares</p>
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
                    <!-- Total General -->
                    <div>
                        <div class="flex justify-between items-center max-w-sm p-3 bg-white border border-[#349fda] rounded-lg shadow">
                            <div class="">
                                <p class="text-xs font-normal text-black uppercase">General($)</p>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2 py-1 mr-auto font-bold text-center text-[#349fda] align-baseline rounded-lg border border-[#349fda]">
                                    <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16M8 14h8m-4-7V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
                                      </svg>

                                   ${{ $total_general }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de servicios -->
                <div class="fex mt-5">
                    <p class="mt-5 mb-1 text-xs font-bold text-black uppercase">Detalle General de Nomina</p>
                </div>

                <div class="relative overflow-x-auto shadow-md rounded-lg border">
                    <table class="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class=" text-[12px] text-black uppercase bg-gray-300">
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
            </div>
        </div>
    </body>
</html>