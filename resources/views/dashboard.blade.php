<x-app-layout>
    @php
        if(Auth::user()->tipo_usuario == 'nomina')
        {
            $style = 'w-1/2';
        }else{
            $style = 'w-full';
        }
    @endphp
    <div class="mt-5 flex flex-col md:justify-center items-center sm:pt-0 bg-white">
        <div class="max-w-7xl mx-auto {{ $style }} ">
            <div class="overflow-hidden">
                @livewire('dashboard')
            </div>
        </div>
    </div>
</x-app-layout>
