<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Credencial de Membresía</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            width: 350px;
            height: 220px;
            margin: 0;
            padding: 10px;
            border: 2px solid #000;
            position: relative;
            background-color: #f4f4f4;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }
        .logo {
            width: 60px;
        }
        .titulo {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            color: #333;
        }
        .foto {
            width: 90px;
            height: 110px;
            border: 2px solid #000;
            background-color: #fff;
            margin-top: 10px;
            position: absolute;
            top: 60px;
            left: 20px;
            border-radius: 5px;
            overflow: hidden;
        }
        .datos {
            margin-left: 130px;
            margin-top: 10px;
            font-size: 14px;
            color: #333;
        }
        .datos p {
            margin: 5px 0;
        }
        .datos p strong {
            color: #000;
        }
        .firma {
            position: absolute;
            bottom: 20px;
            left: 20px;
            width: 120px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 12px;
            color: #333;
        }
        .qr {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
        <div class="titulo">Membresía</div>
    </div>
    <div class="foto">
        @if($membresia->cliente->foto)
            <img src="{{ public_path('storage/' . $membresia->cliente->foto) }}" style="width:100%; height:100%;" alt="Foto">
        @else
            <p style="text-align:center; padding-top:40px;">Sin Foto</p>
        @endif
    </div>
    <div class="datos">
        <p><strong>Nombre:</strong> {{ $membresia->cliente->nombre }} {{ $membresia->cliente->primerApellido }} {{ $membresia->cliente->segundoApellido }}</p>
        <p><strong>Plan:</strong> {{ $membresia->planMembresia->nombrePlan }}</p>
        <p><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($membresia->fechaInicio)->format('d/m/Y') }}</p>
        <p><strong>Fin:</strong> {{ \Carbon\Carbon::parse($membresia->fechaFin)->format('d/m/Y') }}</p>
    </div>
    <div class="firma">
        Firma
    </div>
    <div class="qr">
        <!-- Aquí puedes colocar el código QR -->
    </div>
</body>
</html>
