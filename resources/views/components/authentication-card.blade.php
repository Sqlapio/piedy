<div class="min-h-screen flex flex-col min-[250px]:justify-center min-[250px]:p-4 items-center pt-4 sm:pt-0 bg-[#e4dcdc]">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded-lg">
        {{ $slot }}
    </div>
</div>
