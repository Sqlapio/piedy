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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>

<body>
    <img style="display: block; margin-left: auto; margin-right: auto; width: 600px; height: auto;" src="https://piedy.sqlapio.net/images/banner_correo.jpg">

    <div style="margin: auto; width: 600px; padding: 10px;">
        <p style="text-align: justify; margin-left: 20px;">
            <h2 style="text-align: justify; margin-left: 20px;">Sr(a). Jhonny Martinez</h2>
        </p>
        <p style="text-align: justify; margin-left: 20px;">
            Usted acaba de recibir una GiftCard para que disfrute de nuestros servicios
            <br>
            Siempre pensando en ti y en tu bienestar!
        </p>
            <div class="credit-card visa selectable"
                style="
                    background-image: url('https://piedy.sqlapio.net/images/gift40.png');
                    background-size: 100% 100%;
                    margin: auto;
                    margin-top: 20px;
                    margin-bottom: 20px;
                    border-radius: 7px;
                    width: 400px;
                    height: 195px;
                    max-width: 400px;
                    position: relative;
                    transition: all 0.4s ease;
                    box-shadow: 0 2px 4px 0 #cfd7df;
                    min-height: 195px;
                    padding: 13px;
                ">
                    <div class="credit-card-last4" style="font-size: 13px; padding-top: 105px; padding-left: 25px; color: black;">
                        <div>EXP: 05/24</div>
                    </div>
                <div class="credit-card-last4" style="font-size: 18px; padding-left: 25px; color: black;">
                    JHONNY MARTINEZ <span style="margin-left: 85px; font-size: 13px;">PGC: 6743</span>
                </div>
                <div class="credit-card-expiry">
                    <img style="
                        display: block;
                        margin-left: auto;
                        margin-right: auto;
                        width: 350px;
                        height: auto;
                        " src="https://piedy.sqlapio.net/images/barcode.jpg">
                </div>
            </div>
        <p style="text-align: justify; margin-left: 20px;">
            Para cualquier consulta o asistencia adicional que necesite, puede comunicarse las 24
            horas del dia con nuestro equipo a traves de piedyccs@gmail.com
        </p>
    </div>
</body>
</html>

