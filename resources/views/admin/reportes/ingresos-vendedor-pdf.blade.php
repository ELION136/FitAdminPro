<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ingresos por Vendedor - Gimnasio Urbano</title>
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
            padding-bottom: 100px;
        }

        .header {
            text-align: left;
            padding: 20px 0;
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: start;
        }

        .header img {
            width: 80px;
        }

        .company-info {
            text-align: left;
            font-size: 12px;
            color: #555;
            line-height: 1.5;
        }

        .report-info {
            text-align: right;
            padding: 20px 0;
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

        .document-info {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
            z-index: 2;
            position: relative;
            margin-bottom: 30px;
        }

        table, th, td {
            border: 1px solid #dddddd;
        }

        th {
            padding: 10px;
            background-color: #2c3e50;
            color: white;
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

        .notes {
            margin: 30px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .notes p {
            margin: 0;
            color: #555;
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

        footer {
            text-align: center;
            font-size: 10px;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 2;
            border-top: 1px solid #eee;
            background-color: white;
        }

        .pagenum:before {
            content: counter(page);
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.05);
            z-index: 0;
            text-align: center;
            white-space: nowrap;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <img src="{{ public_path('dist/assets/images/logo1.png') }}" alt="Logo Gimnasio Urbano">
                <div class="company-info">
                    <strong>GIMNASIO URBANO</strong><br>
                    <strong>Dirección:</strong> El Abra, Sacaba, Cochabamba<br>
                    <strong>Teléfono:</strong> 75983258<br>
                    <strong>Email:</strong> info@gimnasiourbano.com<br>
                    <strong>NIT:</strong> 1234567890
                </div>
            </div>
            <div class="document-info">
                <p><strong>Nº Comprobante:</strong> {{ str_pad($data['numeroComprobante'] ?? rand(1000, 9999), 8, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <div class="report-info">
            <h1>Reporte de Ingresos por Vendedor</h1>
            <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>

            @if($fechaInicio && $fechaFin)
                <p>Período: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
            @else
                <p>Rango de fechas no especificado</p>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vendedor</th>
                    <th>Total de Inscripciones</th>
                    <th>Total Ganado (BOB)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ingresosPorVendedor as $index => $ingreso)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $ingreso->vendedor }}</td>
                        <td>{{ $ingreso->totalInscripciones }}</td>
                        <td>{{ number_format($ingreso->totalGanado, 2) }} BOB</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Sin elementos</td>
                    </tr>
                @endforelse
                <!-- Fila de totales -->
                @if($ingresosPorVendedor->isNotEmpty())
                    <tr>
                        <td colspan="2" style="text-align: right; font-weight: bold;">Total General:</td>
                        <td style="font-weight: bold;">{{ $totalInscripciones }}</td>
                        <td style="font-weight: bold;">{{ number_format($totalGanado, 2) }} BOB</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="notes">
            <p><strong>Notas:</strong> Aquí puedes incluir comentarios adicionales o información relevante al reporte.</p>
        </div>

        <div class="signature">
            <p>__________________________</p>
            <p>Firma del Responsable</p>
            <p>Nombre del Responsable</p>
            <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        </div>
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} Gimnasio Urbano | Página <span class="pagenum"></span></p>
    </footer>

    <div class="watermark">
        GIMNASIO URBANO
    </div>
</body>
</html>
