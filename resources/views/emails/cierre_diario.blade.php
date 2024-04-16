<!DOCTYPE html>
<html>
<head>
    <title>Piedy.com</title>
    <style>
        #customers {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        #customers td, #customers th {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #7898a5;
        color: black;
        }

    </style>
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
            <br>
            <strong>TASA BCV: {{ $mailData['tasa_bcv'] }}</strong>
            <br>
            <strong>TOTAL DE SERVICIOS: {{ $mailData['total_servicios'] }}</strong>
            <br>
        </p>
        <div style="margin: auto; width: 600px; padding: 10px;">
            <table id="customers">
                <tr>
                    <th>Denominación</th>
                    <th>Valor</th>
                </tr>
                <tr>
                    <td>Responsable</td>
                    <td>{{ $mailData['responsable'] }}</td>
                </tr>
                <tr>
                    <td>Fecha</td>
                    <td>{{ $mailData['fecha'] }}</td>
                </tr>
                <tr>
                    <td>Total General de Ventas($)</td>
                    <td>{{ round($mailData['total_ventas']) }}</td>
                </tr>
                <tr>
                    <td>Total($)</td>
                    <td>{{ round($mailData['total_dolares']) }}</td>
                </tr>
                <tr>
                    <td>Zelle($)</td>
                    <td>{{ round($mailData['zelle']) }}</td>
                </tr>
                <tr>
                    <td>Total(Bs.)</td>
                    <td>{{ round($mailData['total_bolivares']) }}</td>
                </tr>
                <tr>
                    <td>Conversión (Bs.) -> ($)</td>
                    <td>{{ round($mailData['conversion']) }}</td>
                </tr>
                <tr>
                    <td>Efectivo($) en caja</td>
                    <td>{{ $mailData['efectivo_caja_usd'] }}</td>
                </tr>
                <tr>
                    <td>Gastos($)</td>
                    <td>{{ round($mailData['gastos']) }}</td>
                </tr>
                <tr>
                    <td>Saldo Caja Chica($)</td>
                    <td>{{ $mailData['efectivo_caja_chica'] }}</td>
                </tr>
            </table>
        </div>
        <p style="text-align: justify; margin-left: 20px;">
            Esta notificación confirma que el proceso de facturación asociado a su trabajo ha sido completado correctamente. <br>
            Agradecemos su esfuerzo continuo y la dedicación con la que lleva a cabo cada servicio, lo cual es fundamental para nuestro compromiso con la excelencia. <br>
            Si tiene alguna pregunta o necesita asistencia adicional, no dude en ponerse en contacto con nosotros.
        </p>
    </div>

</body>
</html>
