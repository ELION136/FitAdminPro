<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Control de Asistencia - Servicio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f7f7f7;
        }

        .card {
            width: 360px;
            background-color: #e036c7;
            /* Mostaza */
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #333;
            /* Negro */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
            color: #333;
            /* Negro */
        }

        .header img {
            width: 70px;
            margin-bottom: 10px;
        }

        .header h2 {
            font-size: 22px;
            color: #fff;
            /* Blanco */
            margin: 0;
            text-transform: uppercase;
        }

        .info {
            text-align: left;
            margin-top: 5px;
            font-size: 15px;
            color: #fff;
            /* Blanco */
        }

        .info p {
            margin: 6px 0;
        }

        .highlight {
            font-weight: bold;
            color: #333;
            /* Negro */
        }

        .qr-code img {
            width: 150px;
            margin-top: 5px;
            border: 2px solid #333;
            /* Negro */
            padding: 5px;
            border-radius: 8px;
        }

        .footer {
            font-size: 12px;
            color: #333;
            /* Negro */
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="header">
            <img src="{{ $logo }}" alt="Logo del Gimnasio">
            <h2>targeta - Servicio</h2>
        </div>
        <div class="info">
            <p><span class="highlight">Cliente:</span> {{ $cliente->nombre }} {{ $cliente->primerApellido }}
                {{ $cliente->segundoApellido }}</p>
            <p><span class="highlight">Numero Cliente:</span> 734-{{ $cliente->idCliente }}</p>
            <p><span class="highlight">Servicio:</span> {{ $detalleInscripcion->servicio->nombre }}</p>
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

        <!-- Sección para el QR del Servicio -->
        <div class="qr-code">
            <h4>Código QR del Servicio</h4>
            @if ($detalleInscripcion->qrCode)
                <div class="qr-code mb-3">
                    <img src="{{ asset('storage/' . $detalleInscripcion->qrCode) }}" alt="Código QR del Servicio">
                    <p>Escanea para registrar asistencia en {{ $detalleInscripcion->servicio->nombre }}</p>
                </div>
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
