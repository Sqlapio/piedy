<!DOCTYPE html>
<html>
<head>
    <title>SqlapioTechnology LLC.</title>
</head>
<body>
    <img style="display: block; margin-left: auto; margin-right: auto; width: 600px; height: auto;" src="{{ asset('images/banner_piedy.jpg') }}">
    <div style="margin: auto; width: 600px; padding: 10px;">
        <p style="text-align: justify; margin-left: 20px;">
            <h2 style="text-align: justify; margin-left: 20px;">Estimada Sr(a). {{ $mailData['user_fullname'] }}</h2>
        </p>
        <p style="text-align: justify; margin-left: 20px;">
            Nos complace informarle que su servicio a sido facturado con éxito. <br>
            A continuación, encontrará los detalles pertinentes de la transacción para su registro y referencia:
            <br>
        </p>
        <p style="text-align: justify; margin-left: 20px;">
            <h3 style="text-align: justify; margin-left: 20px;">
                Cliente: {{ $mailData['cliente_fullname'] }}
                <br>
                Fecha: {{ $mailData['fecha_venta'] }}
                <br>
                Codigo de servicio: {{ $mailData['codigo'] }}
            </h3>
        </p>
        <p style="text-align: justify; margin-left: 20px;">
            Detalle de servicio:
        </p>
        @foreach($mailData['detalle'] as $item)
        <h2 style="text-align: justify; margin-left: 20px;">
            - {{ $item->servicio }}
            <br>
        </h2>
        @endforeach
        </p>
        <p style="text-align: justify; margin-left: 20px;">
            Esta notificación confirma que el proceso de facturación asociado a su trabajo ha sido completado correctamente. <br>
            Agradecemos su esfuerzo continuo y la dedicación con la que lleva a cabo cada servicio, lo cual es fundamental para nuestro compromiso con la excelencia. <br>
            Si tiene alguna pregunta o necesita asistencia adicional, no dude en ponerse en contacto con nosotros.
        </p>
    </div>

</body>
</html>
