<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asistencias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #000;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reporte de Asistencias</h2>
        <p>Desde: {{ $request->input('fechaInicio', \Carbon\Carbon::now()->subMonth()->format('Y-m-d')) }} 
           Hasta: {{ $request->input('fechaFin', \Carbon\Carbon::now()->format('Y-m-d')) }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Hora Entrada</th>
                <th>Hora Salida</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asistencias as $asistencia)
                <tr>
                    <td>{{ $asistencia->cliente->nombre }}</td>
                    <td>{{ $asistencia->fecha }}</td>
                    <td>{{ $asistencia->horaEntrada }}</td>
                    <td>{{ $asistencia->horaSalida ?? 'No registrada' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>
</body>
</html>
