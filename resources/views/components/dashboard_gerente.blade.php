<div class="py-1 my-auto">

    {{-- Primera linea para empleados --}}
    @if(Auth::user()->email == 'genesisreggio@gmail.com')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
                {{-- Crear producto --}}
                <div class="p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/abstract-light-blue-background-geometry-pattern-hd-wallpaper_1000823-2187.jpg'); background-size: cover;">
                    <a href="{{ route('cierre_general') }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                        </svg>
                    </div>
                    <div class="ml-12 text-right">
                        <div class="mt-2 text-2xl text-black leading-7 font-bold">
                            CIERRE GENERAL
                        </div>
                            <div class="mt-3 text-right text-md font-semibold text-black">
                                <div>Modulo de cierre de actividades ejecutadas en un periodo de tiempo</div>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- producto Asignado --}}
                <div class="p-6 rounded-lg" style="background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20201015/pngtree-modern-low-poly-background-with-red-and-blue-gradient-colors-image_417695.jpg');background-size: cover;">
                    <a href="{{ route('dashboard') }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
                        </svg>
                    </div>
                    <div class="ml-12 text-right">
                        <div class="mt-2 text-2xl text-black leading-7 font-bold">
                            APP TIENDA
                        </div>
                            <div class="mt-3 text-right text-md font-semibold text-black">
                                <div>Servício asignado</div>
                            </div>
                        </a>
                    </div>
                </div>
        </div>
    @endif

</div>