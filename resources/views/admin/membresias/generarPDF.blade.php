<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            width: 100px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
        }
        .content {
            margin: 0 20px;
        }
        .content h2 {
            font-size: 18px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f5f5f5;
        }
        .table td {
            background-color: #fff;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="logo.png" alt="Logo de la Empresa">
        <h1>Reporte de Membresías</h1>
        <p>Generado el: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>
    
    <div class="content">
        <h2>Detalles del Reporte</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Miembro</th>
                    <th>Plan</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de Fin</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($membresias as $membresia)
                <tr>
                    <td>{{ $membresia->cliente->nombre }} {{ $membresia->cliente->primerApellido }} {{ $membresia->cliente->segundoApellido }}</td>
                    <td>{{ $membresia->planMembresia->nombrePlan }}</td>
                    <td>{{ $membresia->fechaInicio }}</td>
                    <td>{{ $membresia->fechaFin }}</td>
                    <td>{{ $membresia->estado }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Nombre de la Empresa. Todos los derechos reservados.</p>
    </div>
</body>
</html>
