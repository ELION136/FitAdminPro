<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credencial de Gimnasio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .credential {
            width: 350px;
            height: 450px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
            overflow: hidden;
        }

        .header {
            background-color: #3498db;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .content {
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .photo {
            width: 120px;
            height: 120px;
            background-color: #ddd;
            border-radius: 50%;
            margin: 0 auto;
        }

        .info {
            margin-top: 20px;
        }

        .name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            text-align: center;
        }

        .member-info {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            text-align: center;
        }

        .plan-info {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            text-align: center;
        }

        .dates {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            text-align: center;
        }

        .logo {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
            margin-top: 20px;
        }

        .firma {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="credential">
        <div class="header">FitAdminPro</div>
        <div class="content">
            <div class="photo">
                @if ($membresia->cliente->foto)
                    <img src="{{ public_path('storage/' . $membresia->cliente->foto) }}"
                    alt="Foto" style="width: 100%; height: 100%; border-radius: 50%;">
                @else
                    <p style="text-align:center; padding-top:50px;">Sin Foto</p>
                @endif
            </div>
            <div class="info">
                <p class="name">{{ $membresia->cliente->nombre }} {{ $membresia->cliente->primerApellido }} {{ $membresia->cliente->segundoApellido }}</p>
                
                <p class="plan-info">Plan: {{ $membresia->planMembresia->nombrePlan }}</p>
                <p class="dates">Inicio: {{ \Carbon\Carbon::parse($membresia->fechaInicio)->format('d/m/Y') }} - Fin: {{ \Carbon\Carbon::parse($membresia->fechaFin)->format('d/m/Y') }}</p>
            </div>
            <div class="logo">FitAdminPro</div>
            <div class="firma">Firma</div>
        </div>
    </div>
</body>
</html>