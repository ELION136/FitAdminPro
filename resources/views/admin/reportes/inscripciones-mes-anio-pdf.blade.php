<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inscripciones por Día, Mes y Año</title>
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
            padding-bottom: 80px;
        }

        .header {
            text-align: left;
            padding: 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2;
        }

        .header img {
            width: 50px;
        }

        .company-info {
            font-size: 12px;
            color: #555;
            line-height: 1.5;
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

        .total {
            font-weight: bold;
            text-align: right;
        }

        footer {
            text-align: center;
            font-size: 10px;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 2;
        }

        .pagenum:before {
            content: counter(page);
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.05);
            z-index: 0;
            text-align: center;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Encabezado con logo e información de la empresa -->
        <div class="header">
            <img src="{{ public_path('dist/assets/images/logo1.png') }}" alt="Logo">
            <div class="company-info">
                <strong>GIMNASIO URBANO</strong><br>
                <strong>Dirección:</strong> El Abra, Sacaba, Cochabamba<br>
                <strong>Teléfono:</strong> 75983258<br>
                <strong>Email:</strong> info@gimnasiourbano.com<br>
                <strong>NIT:</strong> 1234567890
            </div>
        </div>

        <!-- Información del reporte -->
        <div class="report-info">
            <h1>Reporte de Inscripciones por Día, Mes y Año</h1>
            <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Tabla de datos de inscripciones -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Año</th>
                    <th>Mes</th>
                    <th>Día</th>
                    <th>Total de Inscripciones</th>
                    <th>Total Pagado (BOB)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inscripciones as $index => $inscripcion)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $inscripcion->anio }}</td>
                        <td>{{ \Carbon\Carbon::create()->month($inscripcion->mes)->locale('es')->isoFormat('MMMM') }}</td>
                        <td>{{ $inscripcion->dia }}</td>
                        <td>{{ $inscripcion->totalInscripciones }}</td>
                        <td>{{ number_format($inscripcion->totalPagado, 2) }} BOB</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total global ganado -->
        <h3 class="total">Total Ganado: {{ number_format($totalGanado, 2) }} BOB</h3>

        <!-- Reporte Anual -->
        <h2 style="margin-top: 40px;">Reporte Total Anual</h2>
        <table>
            <thead>
                <tr>
                    <th>Año</th>
                    <th>Total de Inscripciones</th>
                    <th>Total Ganado (BOB)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reporteAnual as $anio)
                    <tr>
                        <td>{{ $anio->anio }}</td>
                        <td>{{ $anio->totalInscripciones }}</td>
                        <td>{{ number_format($anio->totalGanado, 2) }} BOB</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Firma del responsable -->
        <div style="margin-top: 30px; text-align: right;">
            <p>__________________________</p>
            <p>Firma del Responsable</p>
            <p>Nombre del Responsable</p>
            <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Pie de página con numeración -->
    <footer>
        <p>&copy; {{ date('Y') }} Gimnasio Urbano | Página <span class="pagenum"></span></p>
    </footer>

    <!-- Marca de agua -->
    <div class="watermark">
        GIMNASIO URBANO
    </div>
</body>

</html>
