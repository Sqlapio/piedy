<!DOCTYPE html>
<html>
<head>
    <title>SqlapioTechnology LLC.</title>
</head>
<body>
    <img style="display: block; margin-left: auto; margin-right: auto; width: 600px; height: auto;" src="{{ asset('images/banner_correo.jpg') }}">
    <div style="margin: auto; width: 600px; padding: 10px;">
        <p style="text-align: justify; margin-left: 20px;">
            <h2 style="text-align: justify; margin-left: 20px;">Sr(a). {{ $mailData['user_fullname'] }}</h2>
        </p>
        <p style="text-align: justify; margin-left: 20px;">
            Usted acaba actualizar su contraseña.
            <br>
            <br>
            <strong>SI NO RECONOCE ESTA ACCION, POR FAVOR PONERCE EN CONTACTO CON EL ADMINISTRADOR DEL SISTEMA</strong>
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
