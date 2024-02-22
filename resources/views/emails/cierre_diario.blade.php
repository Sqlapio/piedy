<!DOCTYPE html>
<html>
<head>
    <title>Piedy.com</title>
</head>
<body>
    <img style="display: block; margin-left: auto; margin-right: auto; width: 600px; height: auto;" src="{{ asset('images/banner_correo.jpg') }}">
    <div style="margin: auto; width: 600px; padding: 10px;">
        <p style="text-align: justify; margin-left: 20px;">
            <h2 style="text-align: justify; margin-left: 20px;">Administrador</h2>
        </p>
        <p style="text-align: justify; margin-left: 20px;">
            Se informa que el cierre de caja fue ejecutado con éxito. <br>
            A continuación, encontrará el detalle del cierre para su referencia:
            <br>
        </p>
        <p style="text-align: justify; margin-left: 20px;">
            <h3 style="text-align: justify; margin-left: 20px;">
                Responsable: {{ $mailData['responsable'] }}
                <br>
                Fecha: {{ $mailData['fecha'] }}
                <br>
                Total de Ventas: {{ $mailData['total_ventas'] }}
                <br>
                Total($): {{ $mailData['total_dolares'] }}
                <br>
                Zelle($): {{ $mailData['zelle'] }}
                <br>
                Total(Bs.): {{ $mailData['total_bolivares'] }}
                <br>
                Conversión (Bs.) -> ($): {{ $mailData['conversion'] }}
                <br>
                Efectivo($) en caja: {{ $mailData['efectivo_caja_usd'] }}
                <br>
                Gastos($): {{ $mailData['gastos'] }}
                <br>
                Saldo Caja Chica($): {{ $mailData['efectivo_caja_chica'] }}
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
