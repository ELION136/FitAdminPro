

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Asistencia - Servicio</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #ff914d, #2a5298);
        }

        .card {
            width: 320px;
            background-color: #ffffff;
            border-radius: 15px;
            border: 2px solid #333;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .header {
            border-bottom: 2px solid #dcdcdc;
            padding-bottom: 10px;
        }

        .header img {
            width: 60px;
            margin-bottom: 10px;
        }

        .header h2 {
            font-size: 18px;
            color: #333333;
            margin: 0;
            text-transform: uppercase;
            font-weight: bold;
        }

        .info {
            text-align: left;
            margin: 10px 0;
            font-size: 14px;
            color: #333333;
        }

        .info p {
            margin: 4px 0;
        }

        .info ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .info ul li {
            margin: 5px 0;
        }

        .highlight {
            font-weight: bold;
            color: #2a5298;
        }

        .qr-code {
            margin-top: 10px;
        }

        .qr-code img {
            width: 130px;
            margin: 0 auto;
            border: 2px solid #dcdcdc;
            border-radius: 8px;
        }

        .footer {
            position: absolute;
            bottom: 10px;
            left: 20px;
            right: 20px;
            font-size: 12px;
            color: #666666;
            text-align: center;
        }

        /* Fondo decorativo */
        .background-overlay {
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background-color: rgba(255, 145, 77, 0.1);
            border-radius: 50%;
            z-index: -1;
        }

        .background-overlay-secondary {
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 300px;
            height: 300px;
            background-color: rgba(42, 82, 152, 0.1);
            border-radius: 50%;
            z-index: -1;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="background-overlay"></div>
        <div class="background-overlay-secondary"></div>
        <div class="header">
            <img src="{{ $logo }}" alt="Logo del Gimnasio">
            <h2>Control de Servicio</h2>
        </div>
        <div class="info">
            <p><span class="highlight">Cliente:</span> {{ $cliente->nombre }} {{ $cliente->primerApellido }} {{ $cliente->segundoApellido }}</p>
            <p><span class="highlight">Número Cliente:</span> 734-{{ $cliente->idCliente }}</p>
            <p><span class="highlight">Servicio:</span> {{ $detalleInscripcion->servicio->nombre }}</p>
            <hr>
            <h4>Horarios del Servicio</h4>
            @php
                $horariosAgrupados = [];

                foreach ($horarios as $horario) {
                    $horaRango = "{$horario['horaInicio']} - {$horario['horaFin']}";
                    if (!isset($horariosAgrupados[$horaRango])) {
                        $horariosAgrupados[$horaRango] = [];
                    }
                    $horariosAgrupados[$horaRango][] = $horario['dia'];
                }
            @endphp

            <ul>
                @foreach ($horariosAgrupados as $horaRango => $dias)
                    <li>
                        <strong>{{ implode(', ', $dias) }}:</strong> {{ $horaRango }}
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="qr-code">
            <h4>Código QR del Servicio</h4>
            @if ($detalleInscripcion->qrCode)
                <img src="{{ asset('storage/' . $detalleInscripcion->qrCode) }}" alt="Código QR del Servicio">
                <p>Escanea para registrar asistencia en {{ $detalleInscripcion->servicio->nombre }}</p>
            @else
                <p>No se ha generado un código QR para el servicio: {{ $detalleInscripcion->servicio->nombre }}</p>
            @endif
        </div>
        <div class="footer">
            <p>Gimnasio Urbano - Sacaba, El Abra | Tel: 75983258</p>
        </div>
    </div>
</body>

</html>

