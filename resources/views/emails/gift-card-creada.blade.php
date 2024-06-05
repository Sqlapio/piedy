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
            Se informa que  se acaba de registrar una GiftCard con las siguiente. <br>
            especificaciones:
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
                    <td>Código de Seguridad</td>
                    <td>{{ $mailData['codigo_seguridad'] }}</td>
                </tr>
                <tr>
                    <td>Cliente</td>
                    <td>{{ $mailData['cliente'] }}</td>
                </tr>
                <tr>
                    <td>Emitida</td>
                    <td>{{ $mailData['emitida'] }}</td>
                </tr>
                <tr>
                    <td>Vence</td>
                    <td>{{ $mailData['vence'] }}</td>
                </tr>
                <tr>
                    <td>Monto de la GiftCard($)</td>
                    <td>{{ $mailData['monto'] }}</td>
                </tr>
                <tr>
                    <td>Metodo de Pago</td>
                    <td>{{ $mailData['metodo_pago'] }}</td>
                </tr>
                <tr>
                    <td>{{ ($mailData['metodo_pago'] == 'Pago Movil' || $mailData['metodo_pago'] == 'Transferencia') ? 'Monto cancelado en (Bs).' : 'Monto cancelado en ($).' }}</td>
                    <td>{{ $mailData['monto_pagado'] }}</td>
                </tr>
                <tr>
                    <td>Nro. Referencia</td>
                    <td>{{ $mailData['referencia'] }}</td>
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
