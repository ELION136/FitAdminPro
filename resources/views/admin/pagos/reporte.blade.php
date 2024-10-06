<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Pagos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Reporte de Pagos</h1>
    <p>Desde: {{ request('fecha_inicio') }} Hasta: {{ request('fecha_fin') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pagos as $pago)
                <tr>
                    <td>{{ $pago->id }}</td>
                    <td>{{ $pago->cliente->nombre }} {{ $pago->cliente->primerApellido }}</td>
                    <td>{{ $pago->fecha }}</td>
                    <td>{{ $pago->monto }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
