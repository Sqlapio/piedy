<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @notifyCss
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>

<body>
    <img style="display: block; margin-left: auto; margin-right: auto; width: 600px; height: auto;" src="{{ asset('images/banner_correo.jpg') }}">
    <div style="margin: auto; width: 600px; padding: 10px;">
        <div class="bg-white min-h-screen flex justify-center items-center">
            <div class="space-y-16">
                <div class="w-96 h-56 m-auto bg-red-100 rounded-xl relative text-white shadow-2xl transition-transform transform hover:scale-110">
                
                    <img class="relative object-cover w-full h-full rounded-xl" src="https://i.imgur.com/kGkSg1v.png">
                    
                    <div class="w-full px-8 absolute top-8">
                        <div class="flex justify-between">
                            <div class="">
                                <p class="font-light">
                                    Name
                                </h1>
                                <p class="font-medium tracking-widest">
                                    Karthik P
                                </p>
                            </div>
                            <img class="w-14 h-14" src="https://i.imgur.com/bbPHJVe.png"/>
                        </div>
                        <div class="pt-1">
                            <p class="font-light">
                                Card Number
                            </h1>
                            <p class="font-medium tracking-more-wider">
                                4642  3489  9867  7632
                            </p>
                        </div>
                        <div class="pt-6 pr-6">
                            <div class="flex justify-between">
                                <div class="">
                                    <p class="font-light text-xs">
                                        Valid
                                    </h1>
                                    <p class="font-medium tracking-wider text-sm">
                                        11/15
                                    </p>
                                </div>
                                <div class="">
                                    <p class="font-light text-xs text-xs">
                                        Expiry
                                    </h1>
                                    <p class="font-medium tracking-wider text-sm">
                                        03/25
                                    </p>
                                </div>
        
                                <div class="">
                                    <p class="font-light text-xs">
                                        CVV
                                    </h1>
                                    <p class="font-bold tracking-more-wider text-sm">
                                        ···
                                    </p>
                                </div>
                            </div>
                        </div>
        
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
