<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Inscripción</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
        }
        .comprobante-info {
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 10px;
        }
        .qr-code {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $logo }}" alt="Logo del Gimnasio">
        <h1>Comprobante de Inscripción</h1>
    </div>

    <div class="comprobante-info">
        <p><strong>Cliente:</strong> {{ $inscripcion->cliente->nombre }} {{ $inscripcion->cliente->primerApellido }} {{ $inscripcion->cliente->segundoApellido }}</p>
        <p><strong>ID Inscripción:</strong> {{ $inscripcion->idInscripcion }}</p>
        <p><strong>Fecha de Inscripción:</strong> {{ $inscripcion->fechaInscripcion }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Descuento</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inscripcion->detallesInscripciones as $detalle)
                <tr>
                    <td>{{ $detalle->tipoProducto == 'membresia' ? $detalle->membresia->nombre : $detalle->servicio->nombre }}</td>
                    <td>Bs{{ number_format($detalle->precio, 2) }}</td>
                    <td>Bs{{ number_format($detalle->descuento, 2) }}</td>
                    <td>Bs{{ number_format($detalle->precio - $detalle->descuento, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Mostrar el código QR solo si es membresía -->
    @foreach ($inscripcion->detallesInscripciones as $detalle)
        @if($detalle->tipoProducto == 'membresia')
            <div class="qr-code">
                @if($inscripcion->cliente->qrCode)
                    <img src="{{ asset('storage/' . $inscripcion->cliente->qrCode) }}" alt="Código QR" width="200">
                    <p>Escanea este código QR para más detalles</p>
                @else
                    <p>No se ha generado un código QR para este cliente.</p>
                @endif
            </div>
            @break <!-- Si encuentras una membresía, no es necesario seguir verificando -->
        @endif
    @endforeach

    <div class="comprobante-total">
        <h3>Total Pagado: Bs{{ number_format($inscripcion->totalPago, 2) }}</h3>
    </div>
</body>
</html>
