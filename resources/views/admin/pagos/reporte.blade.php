<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Pagos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Pagos</h1>
        <p>Desde {{ $fechaInicio }} hasta {{ $fechaFin }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Plan</th>
                <th>Monto (Bs)</th>
                <th>Fecha de Pago</th>
                <th>Método de Pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pagos as $pago)
                <tr>
                    <td>{{ $pago->membresia->cliente->nombre }} {{ $pago->membresia->cliente->primerApellido }}</td>
                    <td>{{ $pago->membresia->planMembresia->nombrePlan }}</td>
                    <td>{{ $pago->monto }}</td>
                    <td>{{ $pago->fechaPago }}</td>
                    <td>{{ $pago->metodoPago }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Total Pagos: {{ $pagos->count() }}</p>
        <p>Monto Total: {{ $pagos->sum('monto') }}</p>
    </div>
</body>
</html>
