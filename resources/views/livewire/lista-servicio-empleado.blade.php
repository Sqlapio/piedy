<div class="px-4 py-2 mt-2">

    <div class="flex mt-5">
        <h1 class="text-sm mb-4 font-bold text-[#bd9c95] uppercase">reporte de nomina</h1>
    </div>

    <div class="grid md:grid-cols-1 sm:gap-2 md:gap-2">
        <!-- Total de servicios -->
        @foreach($info as $data)
        <div class="py-1">
            <div class="flex justify-between items-center max-w-sm px-4 py-4 bg-white border border-[#349fda] rounded-lg shadow">
                <div class="">
                    <p class="text-xs font-normal text-black uppercase">Reporte</p>
                    <p class="text-xs font-normal text-black uppercase">{{ $data->fecha }}</p>
                </div>
                <div>
                    <span class="text-xs inline-flex items-center px-2 py-2 mr-auto font-bold text-center text-[#349fda] align-baseline rounded-lg border border-[#349fda]">
                          <svg class="w-6 h-6 mr-1 text-red-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2 2 2 0 0 0 2 2h12a2 2 0 0 0 2-2 2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2V4a2 2 0 0 0-2-2h-7Zm-6 9a1 1 0 0 0-1 1v5a1 1 0 1 0 2 0v-1h.5a2.5 2.5 0 0 0 0-5H5Zm1.5 3H6v-1h.5a.5.5 0 0 1 0 1Zm4.5-3a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h1.376A2.626 2.626 0 0 0 15 15.375v-1.75A2.626 2.626 0 0 0 12.375 11H11Zm1 5v-3h.375a.626.626 0 0 1 .625.626v1.748a.625.625 0 0 1-.626.626H12Zm5-5a1 1 0 0 0-1 1v5a1 1 0 1 0 2 0v-1h1a1 1 0 1 0 0-2h-1v-1h1a1 1 0 1 0 0-2h-2Z" clip-rule="evenodd"/>
                          </svg>

                          <a href="{{ url('/'.$data->descripcion) }}" class="px-2 text-sm text-green-700 font-extrabold hover:text-orange-500" target="_blank" rel="noopener noreferrer">Descargar</a>
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- <div class="relative overflow-x-auto shadow-md rounded-lg border">
        <table class="table-fixed w-full text-[8px] text-left text-gray-500 dark:text-gray-400">
            <thead class=" text-[8px] text-black uppercase bg-gray-300">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Fecha
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Descargar
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($info as $data)
                <tr class="odd:bg-white even:bg-gray-200">
                    <td class="px-6 py-2 text-[8px]">
                        <div class="flex flex-col">
                            <div>
                                {{ $data->fecha }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-2 text-[8px]">
                        <div class="flex flex-col">
                            <div>
                                <a href="{{ url('/'.$data->descripcion) }}" class="px-2 text-sm font-extrabold hover:text-orange-500" target="_blank" rel="noopener noreferrer">Descargar</a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="flex p-4 justify-end">
            {{ $info->links('custom-simple-pagination-links-view') }}
        </div>
    </div> --}}
</div>
