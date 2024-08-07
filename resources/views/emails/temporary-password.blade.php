<!DOCTYPE html>
<html>
<head>
    <title>Contraseña Temporal</title>
</head>
<body>
    <h1>Hola, {{ $user->nombreUsuario }}</h1>
    <p>Su cuenta ha sido creada. Su contraseña temporal es: {{ $temporaryPassword }}</p>
    <p>Por favor, haga clic en el siguiente enlace para verificar su correo electrónico y cambiar su contraseña:</p>
    <a href="{{ $verificationUrl }}">Verificar correo y cambiar contraseña</a>
</body>
</html>
