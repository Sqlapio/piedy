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
            Se informa que la GIFTCARD con el Nro. {{ $mailData['codigo_seguridad'] }}, acaba de ser utilizada. <br>
            A continuación el Detalle de la operación:
            <br>
            <br>
            <strong>TASA BCV: {{ $mailData['tasa'] }}</strong>
            <br>
        </p>
        <div style="margin: auto; width: 600px; padding: 10px;">
            <table id="customers">
                <tr>
                    <th>Denominación</th>
                    <th>Valor</th>
                </tr>
                <tr>
                    <td>Código de Asignación del servicio</td>
                    <td>{{ $mailData['codigo_asignacion'] }}</td>
                </tr>
                <tr>
                    <td>Código de la GiftCard</td>
                    <td>{{ $mailData['codigo_seguridad'] }}</td>
                </tr>
                <tr>
                    <td>Cliente</td>
                    <td>{{ $mailData['cliente'] }}</td>
                </tr>
                <tr>
                    <td>Empleado</td>
                    <td>{{ $mailData['tecnico'] }}</td>
                </tr>
                <tr>
                    <td>Fecha de Uso de la GiftCard</td>
                    <td>{{ $mailData['fecha_venta'] }}</td>
                </tr>
                <tr>
                    <td>Servicio</td>
                    <td>{{ $mailData['servicio'] }}</td>
                </tr>
                <tr>
                    <td>Responsable</td>
                    <td>{{ $mailData['responsable'] }}</td>
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
