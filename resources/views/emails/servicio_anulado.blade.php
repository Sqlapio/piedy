<!DOCTYPE html>
<html>
<head>
    <title>Piedy.com</title>
</head>
<body>
    <img style="display: block; margin-left: auto; margin-right: auto; width: 600px; height: auto;" src="{{ asset('images/banner_correo.jpg') }}">
    <div style="margin: auto; width: 600px; padding: 10px;">
        <p style="text-align: justify; margin-left: 20px;">
            <h2 style="text-align: justify; margin-left: 20px;">Estimada Sr(a). {{ $mailData['user_fullname'] }}</h2>
        </p>
        <p style="text-align: justify; margin-left: 20px;">
            Se informa el servicio Nro. <strong>{{ $mailData['codigo'] }}</strong> fue anulado con éxito. <br>
            A continuación, encontrará el detalle de la transacción para su referencia:
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
            Esta notificación confirma que el proceso de facturación asociado a su trabajo ha sido completado correctamente. <br>
            Agradecemos su esfuerzo continuo y la dedicación con la que lleva a cabo cada servicio, lo cual es fundamental para nuestro compromiso con la excelencia. <br>
            Si tiene alguna pregunta o necesita asistencia adicional, no dude en ponerse en contacto con nosotros.
        </p>
    </div>

</body>
</html>
