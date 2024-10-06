<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Reservas</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .header {
            padding: 20px 0;
            border-bottom: 2px solid #007bff;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }

        .logo {
            width: 100px;
            margin-right: 20px;
        }

        .report-title {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
        }

        .meta-info {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 10px 0;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
            text-align: center;
        }

        .page-number:after {
            content: counter(page);
        }

        .totales {
            font-size: 16px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ asset('assets/images/brand-logos/toggle-logo2.png') }}" alt="Logo" class="logo">
        <div>
            <h1 class="report-title">Reporte de Reservas</h1>
            <p class="meta-info">
                Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}<br>
                Total de Reservas: {{ $totalReservas }}<br>
                Total de Pagos Completados: {{ number_format($totalPagosCompletados, 2, ',', '.') }} Bs.<br>
                Generado por: {{ auth()->user()->nombreUsuario }}
            </p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Fecha de Reserva</th>
                <th>Estado</th>
                <th>Monto de Pago (Bs.)</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($reservas as $reserva)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $reserva->cliente->nombre }}</td>
                    <td>{{ $reserva->detalleReservas->first()->horario->servicio->nombre }}</td>
                    <td>{{ \Carbon\Carbon::parse($reserva->fechaReserva)->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($reserva->estado) }}</td>
                    <td>{{ number_format($reserva->total, 2, ',', '.') }} Bs.</td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totales">
        <strong>Total de Reservas: </strong> {{ $totalReservas }} <br>
        <strong>Total de Pagos Completados: </strong> {{ number_format($totalPagosCompletados, 2, ',', '.') }} Bs.
    </div>

    <div class="footer">
        Página <span class="page-number"></span>
    </div>
</body>

</html>
