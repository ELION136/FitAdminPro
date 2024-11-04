<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Control de Asistencia</title>
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
            background-color: #e7ce14;
            /* Mostaza */
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #333;
            /* Negro */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
            color: #333;
            /* Negro */
        }

        .header img {
            width: 80px;
            margin-bottom: 15px;
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
            margin-top: 20px;
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
            width: 160px;
            margin-top: 20px;
            border: 2px solid #333;
            /* Negro */
            padding: 5px;
            border-radius: 8px;
        }

        .footer {
            font-size: 12px;
            color: #333;
            /* Negro */
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="header">
            <img src="{{ $logo }}" alt="Logo del Gimnasio">
            <h2>Control de Asistencia</h2>
        </div>
        <div class="info">
            <p><span class="highlight">Cliente:</span> {{ $inscripcion->cliente->nombre }}
                {{ $inscripcion->cliente->primerApellido }} {{ $inscripcion->cliente->segundoApellido }}</p>
            <p><span class="highlight">Numero Cliente:</span> 724-{{ $inscripcion->cliente->idCliente }}</p>
            <p><span class="highlight">Membresía/Servicio:</span> 
                @foreach($inscripcion->detallesInscripciones as $detalle)
                    @if($detalle->tipoProducto === 'membresia' && $detalle->membresia)
                        {{ ucfirst($detalle->membresia->nombre) }}
                    @elseif($detalle->tipoProducto === 'servicio' && $detalle->servicio)
                        {{ ucfirst($detalle->servicio->nombre) }}
                    @endif
                @endforeach
            </p>
            <p><span class="highlight">Fecha de Inicio:</span>
                {{ $inscripcion->fechaInscripcion ? $inscripcion->fechaInscripcion->format('d/m/Y') : 'N/A' }}</p>
            <p><span class="highlight">Fecha de Fin:</span>
                @if ($inscripcion->detallesInscripciones->first()->tipoProducto == 'membresia')
                    {{ $inscripcion->fechaFin ? \Carbon\Carbon::parse($inscripcion->fechaFin)->format('d/m/Y') : 'N/A' }}
                @else
                    N/A
                @endif
            </p>
        </div>
        <div class="qr-code">
            <!-- Mostrar el QR de la Membresía -->
            <h4>Código QR de Membresía</h4>
            @if ($inscripcion->cliente->qrCode)
                <div class="qr-code mb-3">
                    <img src="{{ asset('storage/' . $inscripcion->cliente->qrCode) }}" alt="Código QR de Membresía"
                        width="150">
                    <p>Escanea para registrar asistencia con Membresía</p>
                </div>
            @else
                <p>No se ha generado un código QR para la membresía de este cliente.</p>
            @endif

            <!-- Mostrar los QRs de Servicios -->
          
        </div>

        <div class="footer">
            <p>Gimnasio Urbano - Sacaba, El Abra | Tel: 75983258</p>
        </div>
    </div>
</body>

</html>
