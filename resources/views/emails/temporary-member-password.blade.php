<!DOCTYPE html>
<html>

<head>
    <title>Detalles de su cuenta y membresía</title>
</head>

<body>
    <h1>¡Bienvenido a nuestro gimnasio, {{ $user->nombreUsuario }}!</h1>

    <p>Se ha creado una cuenta para usted con los siguientes detalles:</p>

    <p><strong>Nombre de usuario:</strong> {{ $user->nombreUsuario }}</p>
    <p><strong>Contraseña temporal:</strong> {{ $temporaryPassword }}</p>

    <p><strong>Detalles de su membresía:</strong></p>
    <ul>
        <li><strong>Plan:</strong> {{ $membresia->planMembresia->nombrePlan }}</li>
        <li><strong>Fecha de inicio:</strong> {{ $membresia->fechaInicio->format('d/m/Y') }}</li>
        <li><strong>Fecha de finalización:</strong> {{ $membresia->fechaFin->format('d/m/Y') }}</li>
        <li><strong>Estado:</strong> {{ $membresia->estado }}</li>
    </ul>

    <p>Por favor, asegúrese de cambiar su contraseña después de iniciar sesión por primera vez.</p>
    <p>Puede acceder a su cuenta y ver los detalles de su membresía en el siguiente enlace:</p>
    <a href="{{ $verificationUrl }}">Iniciar sesión</a>

    <p>Gracias por elegirnos, y esperamos que disfrute de su experiencia en nuestro gimnasio.</p>
</body>

</html>
