<!DOCTYPE html>
<html>
<head>
    <title>SqlapioTechnology LLC.</title>
</head>
<body>
    <img style="display: block; margin-left: auto; margin-right: auto; width: 600px; height: auto;" src="{{ asset('images/banner_notificacion.png') }}">
    <div style="margin: auto; width: 600px; padding: 10px;">
        <p style="text-align: justify; margin-left: 20px;">
            <h2 style="text-align: justify; margin-left: 20px;">Sr(a). {{ $mailData['cliente_fullname'] }}</h2>
        </p>
        <p style="text-align: justify; margin-left: 20px;">
            Usted acaba de agendar una cita en PiedyCcs,
            <br>
            Los detalles a continuacion:
        </p>
        <p style="text-align: justify; margin-left: 20px;">
            <h3 style="text-align: justify; margin-left: 20px;">
                Fecha: {{ $mailData['fecha_cita'] }}
                <br>
                Hora: {{ $mailData['hora_cita'] }}
                <br>
                servicio: {{ $mailData['servicio'] }}
                <br>
                Costo: ${{ $mailData['costo'] }}
            </h3>
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
