<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ingresos por Membresías</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            padding-bottom: 50px;
        }

        .header {
            padding: 20px 0;
            display: flex;
            justify-content: space-between;
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
            margin-top: -60px;
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
        }

        table, th, td {
            border: 1px solid #dddddd;
        }

        th {
            padding: 8px;
            background-color: #eaeaea;
            font-weight: bold;
            text-align: center;
        }

        td {
            padding: 8px;
            text-align: center;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        footer {
            text-align: center;
            font-size: 10px;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: white;
        }

        .pagenum:before {
            content: counter(page);
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .signature {
            margin-top: 50px;
            text-align: center;
            width: 300px;
            float: right;
        }

        .signature p {
            margin: 5px 0;
            color: #333;
        }

        .signature p:first-child {
            border-top: 1px solid #333;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <img src="{{ public_path('dist/assets/images/logo1.png') }}" alt="Logo">
                <p><strong>GIMNASIO URBANO</strong></p>
                <p><strong>Dirección:</strong> El Abra, Sacaba, Cochabamba</p>
                <p><strong>Teléfono:</strong> 75983258</p>
                <p><strong>Email:</strong> info@gimnasiourbano.com</p>
                <p><strong>NIT:</strong> 1234567890</p>
            </div>
            <div class="report-info">
                <h1>Reporte de Ingresos por Membresías</h1>
                <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
                @if($fechaInicio && $fechaFin)
                    <p>Desde: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} hasta {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
                @else
                    <p>Rango de fechas no especificado</p>
                @endif
            </div>
        </div>

        <!-- Table of Incomes -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Membresía</th>
                    <th>Vendedor</th>
                    <th>Total Pagado (BOB)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ingresosPorMembresias as $index => $ingreso)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $ingreso->clienteNombre }} {{ $ingreso->clienteApellido }}</td>
                        <td>{{ $ingreso->membresiaNombre }}</td>
                        <td>{{ $ingreso->vendedor }}</td>
                        <td>{{ number_format($ingreso->totalPagado, 2) }} BOB</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Sin elementos</td>
                    </tr>
                @endforelse
                <!-- Totals Row -->
                @if($ingresosPorMembresias->isNotEmpty())
                    <tr>
                        <td colspan="4" style="text-align: right; font-weight: bold;">Total General:</td>
                        <td style="font-weight: bold;">{{ number_format($totalGanado, 2) }} BOB</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Totals Summary -->
        <div style="margin-top: 20px;">
            <h3>Total Inscripciones: {{ $totalInscripciones }}</h3>
            <h3>Total Ganado: {{ number_format($totalGanado, 2) }} BOB</h3>
        </div>

        <!-- Signature Section -->
        <div class="signature">
            <p>__________________________</p>
            <p>Firma del Responsable</p>
            <p>Nombre del Responsable</p>
            <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} Gimnasio Urbano | Página <span class="pagenum"></span></p>
    </footer>

    <!-- Watermark -->
    <div class="watermark">
        <img src="{{ public_path('dist/assets/images/logo1.png') }}" alt="Logo" style="width: 200px; opacity: 0.05;">
        <p>GIMNASIO URBANO</p>
    </div>
</body>
</html>
