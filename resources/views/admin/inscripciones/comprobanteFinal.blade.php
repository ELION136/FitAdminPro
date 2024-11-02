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
            background-color: #f1bb46; /* Mostaza */
            padding: 20px; 
            border-radius: 10px; 
            border: 2px solid #333; /* Negro */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); 
            text-align: center;
            color: #333; /* Negro */
        }
        .header img { 
            width: 80px; 
            margin-bottom: 15px; 
        }
        .header h2 { 
            font-size: 22px; 
            color: #fff; /* Blanco */
            margin: 0; 
            text-transform: uppercase; 
        }
        .info { 
            text-align: left; 
            margin-top: 20px; 
            font-size: 15px; 
            color: #fff; /* Blanco */
        }
        .info p { 
            margin: 6px 0; 
        }
        .highlight { 
            font-weight: bold; 
            color: #333; /* Negro */
        }
        .qr-code img { 
            width: 160px; 
            margin-top: 20px; 
            border: 2px solid #333; /* Negro */
            padding: 5px; 
            border-radius: 8px; 
        }
        .footer { 
            font-size: 12px; 
            color: #333; /* Negro */
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
            <p><span class="highlight">Cliente:</span> {{ $inscripcion->cliente->nombre }} {{ $inscripcion->cliente->primerApellido }} {{ $inscripcion->cliente->segundoApellido }}</p>
            <p><span class="highlight">ID Cliente:</span> {{ $inscripcion->cliente->idCliente }}</p>
            <p><span class="highlight">Membresía/Servicio:</span> {{ ucfirst($inscripcion->tipoProducto) }}</p>
            <p><span class="highlight">Estado:</span> {{ ucfirst($inscripcion->estado) }}</p>
            <p><span class="highlight">Fecha de Inicio:</span> {{ $inscripcion->fechaInicio ? $inscripcion->fechaInicio->format('d/m/Y') : 'N/A' }}</p>
            <p><span class="highlight">Fecha de Fin:</span> {{ $inscripcion->fechaFin ? $inscripcion->fechaFin->format('d/m/Y') : 'N/A' }}</p>
        </div>
        <div class="qr-code">
            @if($inscripcion->cliente->qrCode)
                <img src="{{ asset('storage/' . $inscripcion->cliente->qrCode) }}" alt="Código QR para control de asistencia">
                <p>Escanea para registrar asistencia</p>
            @else
                <p>No se ha generado un código QR para este cliente.</p>
            @endif
        </div>
        <div class="footer">
            <p>Gimnasio Urbano - Sacaba, El Abra | Tel: 75983258</p>
        </div>
    </div>
</body>
</html>
