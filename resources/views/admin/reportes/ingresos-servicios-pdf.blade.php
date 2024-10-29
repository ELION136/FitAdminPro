<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ingresos por Servicios</title>
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
            <h1>Reporte de Ingresos por Servicios</h1>
            <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
            @if($fechaInicio && $fechaFin)
                <p>Desde: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} hasta {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
            @else
                <p>Rango de fechas no especificado</p>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Vendedor</th>
                    <th>Total Pagado (BOB)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ingresosPorServicios as $ingreso)
                    <tr>
                        <td>{{ $ingreso->clienteNombre }} {{ $ingreso->clienteApellido }}</td>
                        <td>{{ $ingreso->servicioNombre }}</td>
                        <td>{{ $ingreso->vendedor }}</td>
                        <td>{{ number_format($ingreso->totalGanado, 2) }} BOB</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3>Total Inscripciones: {{ $totalInscripciones }}</h3>
        <h3>Total Ganado: {{ number_format($totalGanado, 2) }} BOB</h3>
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
