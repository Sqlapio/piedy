<div>
    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 h-60">
            {{-- Perfil --}}
            <div>
                <a href="{{ route('gift-card') }}">
                    <div class="p-6 rounded-lg object-cover" style="background-image: url('{{ asset('images/gift-card1.png') }}'); background-size: cover;">
                        {{-- <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                        </svg>
                    </div> --}}
                        <div class="text-left">
                            <div class="mt-16 text-2xl text-black leading-7 font-bold">
                                GIFTCARD
                            </div>
                            <div class="mt-3 text-right text-md font-semibold text-black">

                            </div>
                        </div>
                    </div>
                </a>
            </div>
            {{-- Historico --}}
            <div>
                <a href="{{ route('membresia') }}">
                    <div class=" p-6 rounded-lg" style="background-image: url('{{ asset('images/membresia.jpg') }}');background-size: cover;">
                        {{-- <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div> --}}
                    <div class="text-left">
                        <div class="mt-16 text-2xl text-black leading-7 font-bold">
                            MEMBRESIA
                        </div>
                        <div class="mt-3 text-right text-md font-semibold text-black">

                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="w-full h-28"></div>
</div>
<x-menu_table />
</div>

