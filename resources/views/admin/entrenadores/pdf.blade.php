<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Entrenadores</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .header {
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #007bff;
            margin-bottom: 30px;
        }
        .header img {
            width: 80px;
        }
        .report-title {
            font-size: 26px;
            color: #007bff;
            margin: 0;
        }
        .meta-info {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
            text-align: center;
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
            font-size: 14px;
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
            padding: 10px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        .page-number:after {
            content: counter(page);
        }
        @page {
            margin: 1cm;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <img src="{{ url('assets/images/brand-logos/toggle-logo2.png') }}" alt="Logo">
        <h1 class="report-title">Reporte de Entrenadores</h1>
    </div>

    <!-- Información meta del reporte -->
    <div class="meta-info">
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Total de entrenadores: {{ $entrenadores->count() }}</p>
    </div>

    <!-- Tabla de datos -->
    <table>
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>Especialidad</th>
                <th>Género</th>
                <th>Edad</th>
                <th>Estado</th>
                <th>Fecha de Nacimiento</th>
                <th>Fecha de Contratación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entrenadores as $entrenador)
            <tr>
                <td>{{ $entrenador->nombre }} {{ $entrenador->primerApellido }} {{ $entrenador->segundoApellido }}</td>
                <td>{{ $entrenador->usuario->email }}</td>
                <td>{{ $entrenador->especialidad }}</td>
                <td>{{ $entrenador->genero }}</td>
                <td>{{ \Carbon\Carbon::parse($entrenador->fechaNacimiento)->age }} años</td>
                <td>{{ $entrenador->eliminado ? 'Activo' : 'Inactivo' }}</td>
                <td>{{ \Carbon\Carbon::parse($entrenador->fechaCreacion)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($entrenador->fechaContratacion)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer con paginación -->
    <div class="footer">
        Página <span class="page-number"></span>
    </div>

    <!-- Page Break for multi-page reports -->
    <div class="page-break"></div>
</body>
</html>
