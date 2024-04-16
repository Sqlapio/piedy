<div>
    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
            {{-- Perfil --}}
            <div class="p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/abstract-light-blue-background-geometry-pattern-hd-wallpaper_1000823-2187.jpg'); background-size: cover;">
                <a href="{{ route('crear_producto') }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                    </svg>
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-2xl text-black leading-7 font-bold">
                       CREAR PRODUCTO
                    </div>
                        <div class="mt-3 text-right text-md font-semibold text-black">
                            <div>Modulo para la creación de productos</div>
                        </div>
                    </a>
                </div>
            </div>
            {{-- Historico --}}
            <div class="p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/abstract-light-blue-background-hd-wallpaper_1000823-2469.jpg?size=626&ext=jpg&ga=GA1.1.1016474677.1696809600&semt=ais');background-size: cover;">
                <a href="{{ route('vender_producto') }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-2xl text-black leading-7 font-bold">
                        VENDER PRODUCTO
                    </div>
                        <div class="mt-3 text-right text-md font-semibold text-black">
                            <div>Venta de productos a clientes</div>
                        </div>
                    </a>
                </div>
            </div>
    </div>
        <div class="w-full h-28"></div>
    </div>
    <x-menu_table />
</div>

