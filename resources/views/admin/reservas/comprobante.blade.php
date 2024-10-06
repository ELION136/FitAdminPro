<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Reserva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            max-width: 600px;
            background-color: #f9f9f9;
        }
        h1, h2, h3, h4 {
            color: #34495e;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .info-value {
            display: inline-block;
            width: calc(100% - 160px);
        }
        .detail-item {
            margin-bottom: 5px;
        }
        .total {
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Comprobante de Reserva</h1>
        <p>Gracias por confiar en nuestros servicios</p>
    </div>

    <div class="section">
        <p><span class="info-label">Fecha de Reserva:</span> <span class="info-value">{{ $reserva->fechaReserva->format('d/m/Y') }}</span></p>
        <p><span class="info-label">Cliente:</span> <span class="info-value">{{ $reserva->cliente->nombre }}</span></p>
    </div>

    <div class="section">
        <h2>Detalle de la Reserva</h2>
        @if($reserva->detalles && $reserva->detalles->isNotEmpty())
            @foreach($reserva->detalles as $detalle)
                <div class="detail-item">
                    <p><span class="info-label">Servicio:</span> <span class="info-value">{{ $detalle->horario->servicio->nombre }}</span></p>
                    <p><span class="info-label">Horario:</span> <span class="info-value">{{ $detalle->horario->diaSemana }} ({{ $detalle->horario->horaInicio }} - {{ $detalle->horario->horaFin }})</span></p>
                    <p><span class="info-label">Cantidad:</span> <span class="info-value">{{ $detalle->cantidad }}</span></p>
                    <p><span class="info-label">Precio Unitario:</span> <span class="info-value">{{ number_format($detalle->precioUnitario, 2) }} $</span></p>
                    <p><span class="info-label">Subtotal:</span> <span class="info-value">{{ number_format($detalle->subtotal, 2) }} $</span></p>
                    <hr>
                </div>
            @endforeach
        @else
            <p>No hay detalles disponibles para esta reserva.</p>
        @endif
    </div>

    <div class="section">
        <p class="total"><span class="info-label">Total:</span> <span class="info-value">{{ number_format($reserva->total, 2) }} $</span></p>
        <p class="total"><span class="info-label">Descuento aplicado:</span> <span class="info-value">{{ number_format($reserva->descuento, 2) }} $</span></p>
    </div>

    <div class="section">
        <h4>¡Gracias por tu reserva!</h4>
        <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
    </div>

</body>
</html>
