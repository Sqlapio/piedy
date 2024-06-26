<div>
    {{-- tercera linea --}}
    <div class="p-10">
        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-2 px-3">
            {{-- Empleados --}}
            <div wire:click="redir({{ 1 }})" class="p-6 rounded-lg" style="background-image: url('https://p4.wallpaperbetter.com/wallpaper/491/487/757/low-poly-abstract-minimalism-wallpaper-preview.jpg'); background-size: cover;">
                <div class="mt-28 ml-12 text-right">
                    <div class="mt-auto text-4xl text-white leading-7 font-bold">
                        QUIROPEDISTA
                    </div>
                    <div class="sm:hidden md:hidden lg:block mt-3 text-right text-xs font-semibold text-white">
                        <div>Calculo nomina</div>
                    </div>
                </div>
            </div>
            {{-- Nomina --}}
            <div wire:click="redir({{ 2 }})" class="p-6 rounded-lg" style="background-image: url('https://p4.wallpaperbetter.com/wallpaper/491/487/757/low-poly-abstract-minimalism-wallpaper-preview.jpg');background-size: cover;">
                <div class="mt-28 ml-12 text-right">
                    <div class="sm:hidden md:hidden lg:block mt-2 text-4xl text-white text- leading-7 font-bold">
                        MANICURISTA
                    </div>
                        <div class="mt-3 text-right text-xs font-semibold text-white">
                            <div>Calculo nomina</div>
                        </div>
                </div>
            </div>
            {{-- Nomina --}}
            <div wire:click="redir({{ 3 }})" class="p-6 rounded-lg" style="background-image: url('https://p4.wallpaperbetter.com/wallpaper/491/487/757/low-poly-abstract-minimalism-wallpaper-preview.jpg');background-size: cover;">
                <div class="mt-28 ml-12 text-right">
                    <div class="sm:hidden md:hidden lg:block mt-2 text-4xl text-white text- leading-7 font-bold">
                        ENCARGADO
                    </div>
                        <div class="mt-3 text-right text-xs font-semibold text-white">
                            <div>Calculo nomina</div>
                        </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
