<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .content {
            max-width: 600px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .content h1 {
            color: #007bff;
        }
        .content p {
            line-height: 1.6;
            color: #555555;
        }
        .button {
            display: inline-block;
            padding: 12px 20px;
            margin-top: 20px;
            color: #ffffff;
            background-color: #ca6f1a;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.875em;
            color: #888888;
        }
        .logo {
            width: 100px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">


            <h1>Restablecer tu Contraseña</h1>
            <p>Hola,</p>
            <p>Has solicitado restablecer tu contraseña. Haz clic en el botón a continuación para restablecerla:</p>
            <a href="{{ url('/custom-reset-password?token=' . $token . '&email=' . $email) }}" class="button">Restablecer Contraseña</a>
            <p class="footer">Este enlace expirará en una hora. Si no solicitaste este cambio, ignora este correo electrónico.</p>
        </div>
    </div>
</body>
</html>

