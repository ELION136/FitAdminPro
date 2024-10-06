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
        th, td {
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
        .additional-info {
            margin-top: 20px;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('assets/images/brand-logos/toggle-logo2.png') }}" alt="Logo" class="logo">
        <div>
            <h1 class="report-title">Reporte de Clientes</h1>
            <p class="meta-info">
                Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}<br>
                Total de Clientes: {{ $clientes->count() }}<br>
                Generado por: {{ auth()->user()->nombreUsuario }}
            </p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>Género</th>
                <th>Fecha Nacimiento</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Fecha de Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
            <tr>
                <td>{{ $cliente->nombre }} {{ $cliente->primerApellido }} {{ $cliente->segundoApellido }}</td>
                <td>{{ $cliente->usuario->email }}</td>
                <td>{{ $cliente->genero }}</td>
                <td>{{ \Carbon\Carbon::parse($cliente->fechaNacimiento)->format('d/m/Y') }}</td>
                <td>{{ $cliente->telefono }}</td>
                <td>{{ $cliente->estado ? 'Activo' : 'Inactivo' }}</td>
                <td>{{ \Carbon\Carbon::parse($cliente->fechaCreacion)->format('d/m/Y H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="additional-info">
        <p>Este reporte incluye información sobre los clientes registrados en el sistema, incluyendo su estado y datos de contacto.</p>
    </div>

    <div class="footer">
        Página <span class="page-number"></span>
    </div>


</body>
</html>
