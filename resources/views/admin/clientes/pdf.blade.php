<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Clientes</title>
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
        }
        .logo {
            width: 100px;
            float: left;
        }
        .report-title {
            font-size: 24px;
            font-weight: bold;
            margin-left: 120px;
            padding-top: 10px;
        }
        .meta-info {
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #007bff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 10px 0;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        .page-number:after {
            content: counter(page);
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('assets/images/brand-logos/toggle-logo2.png') }}" alt="Logo" class="logo">


        <h1 class="report-title">Reporte de Clientes</h1>
    </div>

    <div class="meta-info">
        Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}<br>
        Total de Clientes: {{ $clientes->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>genero</th>
                <th>Fecha Nacimiento</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
            <tr>
                <td>{{ $cliente->nombre }} {{ $cliente->primerApellido }}</td>
                <td>{{ $cliente->usuario->email }}</td>
                <td>{{ $cliente->genero }}</td>
                <td>{{ $cliente->fechaNacimiento->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Página <span class="page-number"></span>
    </div>
</body>
</html>