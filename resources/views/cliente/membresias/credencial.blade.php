<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credencial del Gimnasio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .card {
            width: 350px;
            height: 200px;
            border: 2px solid #eaeaea;
            border-radius: 10px;
            padding: 10px;
            box-sizing: border-box;
            position: relative;
            background-color: #0d9eec;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            max-width: 50px;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 16px;
            margin: 0;
        }

        .info {
            font-size: 11px;
        }

        .info p {
            margin: 2px 0;
        }

        .section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .photo {
            width: 60px;
            height: 60px;
            background-color: #ddd;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
            line-height: 60px;
            font-size: 10px;
            color: #999;
        }

        .details {
            flex: 1;
            padding-left: 10px;
        }

        .footer {
            position: absolute;
            bottom: 5px;
            text-align: center;
            width: 100%;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="card">
    
    <div class="section">
        <div class="details">
            <p><strong>Nombre:</strong> {{ $cliente->nombre }} {{ $cliente->apellido }}</p>
            <p><strong>Fecha Nac.:</strong> {{ $cliente->fechaNacimiento->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="info">
        <p><strong>Membresía:</strong> {{ $membresia->membresia->nombre }}</p>
        <p><strong>Inicio:</strong> {{ $membresia ->fechaInicio->format('d/m/Y') }}</p>
        <p><strong>Fin:</strong> {{ $membresia ->fechaFin->format('d/m/Y') }}</p>
    </div>

    <div class="footer">
        <p>Validez hasta {{ $membresia->fechaFin->format('d/m/Y') }}</p>
    </div>
</div>

</body>
</html>
