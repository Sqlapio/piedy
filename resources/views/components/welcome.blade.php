<div class="py-8 my-auto">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Clientes --}}
        <div class="p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/abstract-light-blue-background-geometry-pattern-hd-wallpaper_1000823-2187.jpg'); background-size: cover;">
            <a href="{{ route('clientes') }}">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                  </svg>
                  
            </div>
            <div class="ml-12 text-right">
                <div class="mt-2 text-2xl text-black leading-7 font-bold">
                    CLIENTES
                </div>
                    <div class="mt-3 text-right text-md font-semibold text-black">
                        <div>Registro y gestion de clientes</div>
                    </div>
                </a>
            </div>
        </div>
        {{-- Empleados --}}
        <div class="p-6 rounded-lg" style="background-image: url('/images/empleados.jpg'); background-size: cover;">
            <a href="{{ route('empleados') }}">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                  </svg>
                  
            </div>
            <div class="ml-12 text-right">
                <div class="mt-2 text-2xl text-black leading-7 font-bold">
                    EMPLEADOS
                </div>
                    <div class="mt-3 text-right text-md font-semibold text-black">
                        <div>Registro y gestion de empleados</div>
                    </div>
                </a>
            </div>
        </div>
        {{-- Agenda --}}
        <div class="p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/abstract-light-blue-background-hd-wallpaper_1000823-2469.jpg?size=626&ext=jpg&ga=GA1.1.1016474677.1696809600&semt=ais');background-size: cover;">
            <a href="{{ route('citas') }}">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                  </svg>
                                    
                  
            </div>
            <div class="ml-12 text-right">
                <div class="mt-2 text-2xl text-black leading-7 font-bold">
                    AGENDA
                </div>
                    <div class="mt-3 text-right text-md font-semibold text-black">
                        <div>Agenda tus clientes</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8 mb-4">
        {{-- Productos --}}
        <div class="p-6 rounded-lg" style="background-image: url('https://cdn.pixabay.com/photo/2017/03/25/18/06/color-2174066_640.png'); background-size: cover;">
            <a href="">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                  </svg>
                  
            </div>
            <div class="ml-12 text-right">
                <div class="mt-2 text-2xl text-black leading-7 font-bold">
                    PRODUCTOS
                </div>
                    <div class="mt-3 text-right text-md font-semibold text-black">
                        <div>Carga y gestion de productos</div>
                    </div>
                </a>
            </div>
        </div>
        {{-- Servicios --}}
        <div class="p-6 rounded-lg" 
                style="background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20201015/pngtree-modern-low-poly-background-with-red-and-blue-gradient-colors-image_417695.jpg');
            background-size: cover;">
            <a href="{{ route('servicios') }}">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                  </svg>
                  
            </div>
            <div class="ml-12 text-right">
                <div class="mt-2 text-2xl text-black text- leading-7 font-bold">
                    SERVICIOS
                </div>
                    <div class="mt-3 text-right text-md font-semibold text-black">
                        <div>Carga y gestion de servicios</div>
                    </div>
                </a>
            </div>
        </div>
        {{-- Ventas --}}
        <div class="p-6 rounded-lg" 
                style="background-image: url('https://static.vecteezy.com/system/resources/previews/000/406/488/original/background-wallpaper-with-polygons-in-gradient-colors-vector.jpg');
                background-size: cover;">
            <a href="">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                  </svg>
                  
            </div>
            <div class="ml-12 text-right">
                <div class="mt-2 text-2xl text-black leading-7 font-bold">
                    VENTAS
                </div>
                    <div class="mt-3 text-right text-md font-semibold text-black">
                        <div>Modulos de ventas</div>
                    </div>
                </a>
            </div>
        </div>
        {{-- Cabinas --}}
        <div class="p-6 rounded-lg" 
                style="background-image: url('https://img.freepik.com/fotos-premium/primer-plano-fondo-abstracto-colorido-triangulos-ai-generativo_561855-19933.jpg');
                background-size: cover;">
            <a href="{{ route('cabinas') }}">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                  </svg>
                  
            </div>
            <div class="ml-12 text-right">
                <div class="mt-2 text-2xl text-black leading-7 font-bold">
                    CABINAS
                </div>
                    <div class="mt-3 text-right text-md font-semibold text-black">
                        <div>Estaciones de Trabajo</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

