<!DOCTYPE html>
<html>
<head>
    <title>SqlapioTechnology LLC.</title>
</head>
<body>
    <img style="display: block; margin-left: auto; margin-right: auto; width: 600px; height: auto;" src="{{ asset('images/banner_notificacion.png') }}">
    <div style="margin: auto; width: 600px; padding: 10px;">
        <p style="text-align: justify; margin-left: 20px;">
            <h2 style="text-align: justify; margin-left: 20px;">Sr(a). {{ $mailData['user_fullname'] }}</h2>
        </p>
        <p style="text-align: justify; margin-left: 20px;">
            Su servício fue facturado de forma exitosa,
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
            Detalle:
        </p>
        @foreach($mailData['detalle'] as $item)
        <h3 style="text-align: justify; margin-left: 20px;">
            - {{ $item->servicio }}
            <br>
        </h3>
        @endforeach
        </p>
        <p style="text-align: justify; margin-left: 20px;">
            Para cualquier consulta o asistencia adicional que necesite, puede comunicarse las 24
            horas del dia con nuestro equipo a traves de piedyccs@gmail.com
        </p>
        {{-- <p style="text-align: justify; margin-left: 20px; font-size: 9px">
            <br>
            Atentamente,
            <br>
            <br>
            <img style="
                    display: block;
                    margin-left: 0px;
                    width: 80px;
                    height: auto;" src="{{ asset('img/notification_email/fir_jm.png') }}">
            Ing. Jhonny Martinez<br>CEO
        </p> --}}
    </div>

</body>
</html>
