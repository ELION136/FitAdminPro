<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credencial de Asistencia</title>
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
            width: 300px;
            height: 500px;
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

        .photo {
            margin: 2px 0;
        }

        .photo img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #261b15;
        }

        .info {
            margin: 10px 0;
            text-align: left;
            font-size: 14px;
            color: #333333;
        }

        .info p {
            margin: 2px 0;
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
            <h2>Credencial de Asistencia</h2>
        </div>
        <div class="photo">
            <img src="{{ $inscripcion->cliente->image ? asset('storage/' . $inscripcion->cliente->image) : asset('images/default-profile.png') }}" alt="Foto del Cliente">
        </div>
        

        <div class="info">
            <p><span class="highlight">Cliente:</span> {{ $inscripcion->cliente->nombre }} 
                {{ $inscripcion->cliente->primerApellido }} {{ $inscripcion->cliente->segundoApellido }}</p>
            <p><span class="highlight">Número Cliente:</span> 724-{{ $inscripcion->cliente->idCliente }}</p>
            <p><span class="highlight">Membresía:</span> 
                @foreach($inscripcion->detallesInscripciones as $detalle)
                    @if($detalle->tipoProducto === 'membresia' && $detalle->membresia)
                        {{ ucfirst($detalle->membresia->nombre) }}
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
            <h4>Código QR</h4>
            @if ($inscripcion->cliente->qrCode)
                <img src="{{ asset('storage/' . $inscripcion->cliente->qrCode) }}" alt="Código QR">
            @else
                <p>No se ha generado un código QR.</p>
            @endif
        </div>
        <div class="footer">
            <p>Gimnasio Urbano | Sacaba, El Abra<br>Tel: 75983258</p>
        </div>
    </div>
</body>

</html>
