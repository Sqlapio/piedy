<div class="flex items-center">
    <img id="theme-mode"
     x-data="{ darkMode: document.documentElement.classList.contains('dark') }"
     x-init="$watch('darkMode', value => $el.src = value ? '{{ asset('images/BCV.png') }}' : '{{ asset('images/BCV2.png') }}')"
     :src="darkMode ? '{{ asset('images/BCV.png') }}' : '{{ asset('images/BCV2.png') }}'"
     class="w-20 h-auto p-3 rounded-full"
     alt="Logo">
    <div class="ml-2 text-left">
        <div class="mt-2 text-sm text-black leading-7 font-bold">
            BCV: 55.44Bs
        </div>
    </div>
</div>

{{-- {{ TasaBcv::first()->tasa }} --}}

