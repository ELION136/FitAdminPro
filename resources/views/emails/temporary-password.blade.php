<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contraseña Temporal - Gimnasio Urbano</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #007BFF;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .header img {
            height: 50px;
        }
        .content {
            padding: 30px 20px;
            text-align: center;
        }
        .content h1 {
            color: #333333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .content p {
            color: #666666;
            font-size: 16px;
            margin-bottom: 30px;
        }
        .content a {
            background-color: #007BFF;
            color: #ffffff;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            font-size: 16px;
        }
        .footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('dist/assets/images/logo3.png') }}" alt="Gimnasio Urbano" height="50">
            <h2>Gimnasio Urbano</h2>
        </div>
        <div class="content">
            <h1>¡Hola, {{ $user->nombreUsuario }}!</h1>
            <p>Su cuenta en el Gimnasio Urbano ha sido creada exitosamente.</p>
            <p>Su contraseña temporal es: <strong>{{ $temporaryPassword }}</strong></p>
            <p>Para verificar su correo electrónico y cambiar su contraseña, haga clic en el siguiente enlace:</p>
            <a href="{{ $verificationUrl }}">Verificar correo y cambiar contraseña</a>
        </div>
        <div class="footer">
            <p>&copy; 2024 Gimnasio Urbano. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
