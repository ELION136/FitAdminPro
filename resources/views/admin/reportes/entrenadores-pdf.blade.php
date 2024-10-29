<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Entrenadores</title>
    <style>
        <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background-color: #fff;
            position: relative;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .header {
            text-align: left;
            padding: 20px 0;
            position: relative;
            z-index: 2;
        }

        .header img {
            width: 50px; /* Ajuste de tamaño del logo */
        }

        .report-info {
            text-align: right;
            margin-top: -80px;
        }

        .report-info h1 {
            font-size: 20px;
            color: #333;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .report-info p {
            margin: 0;
            font-size: 12px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
            z-index: 2;
            position: relative;
        }

        table, th, td {
            border: 1px solid #dddddd;
        }

        th {
            padding: 8px;
            background-color: #eaeaea;
            font-weight: bold;
            text-align: left;
        }

        td {
            padding: 8px;
            text-align: left;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        footer {
            text-align: center;
            font-size: 10px;
            padding: 10px;
            margin-top: 20px;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 2;
        }

        /* Para compatibilidad con DomPDF */
        .pagenum:before {
            content: counter(page);
        }

        /* Marca de agua */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 100px;
            color: rgba(0, 0, 0, 0.05); /* Texto semitransparente */
            z-index: 0;
            text-align: center;
            white-space: nowrap;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('dist/assets/images/logo1.png') }}" alt="Logo">
        </div>

        <div class="report-info">
            <h1>Reporte de Entrenadores</h1>
            <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
            <p>Sucursal: Nombre de la Sucursal</p>

            <!-- Mostrar el rango de fechas si está disponible -->
            @if($fechaCreacionInicio && $fechaCreacionFin)
                <p>Desde: {{ \Carbon\Carbon::parse($fechaCreacionInicio)->format('d/m/Y') }} hasta {{ \Carbon\Carbon::parse($fechaCreacionFin)->format('d/m/Y') }}</p>
            @else
                <p>Rango de fechas no especificado</p>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Primer Apellido</th>
                    <th>Segundo Apellido</th>
                    <th>Especialidad</th>
                    <th>Género</th>
                    <th>Teléfono</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Fecha de Contratación</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($entrenadores as $entrenador)
                    <tr>
                        <td>{{ $entrenador->nombre }}</td>
                        <td>{{ $entrenador->primerApellido }}</td>
                        <td>{{ $entrenador->segundoApellido }}</td>
                        <td>{{ $entrenador->especialidad }}</td>
                        <td>{{ $entrenador->genero }}</td>
                        <td>{{ $entrenador->telefono }}</td>
                        <td>{{ \Carbon\Carbon::parse($entrenador->fechaNacimiento)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($entrenador->fechaContratacion)->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} Nombre de la Empresa | Página <span class="pagenum"></span></p>
    </footer>

    <div class="watermark">
        <img src="{{ public_path('dist/assets/images/logo1.png') }}" alt="Logo" style="opacity: 0.1; width: 300px; display: block; margin: 0 auto;" />
        <p>FitAdminPro</p>
    </div>
</body>
</html>
