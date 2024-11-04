<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ingresos - Gimnasio Urbano</title>
    <style>
        @page {
            margin: 100px 25px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #333;
            line-height: 1.4;
        }

        /* Header mejorado */
        .header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 70px;
            padding: 20px;
            background-color: #fff;
            border-bottom: 2px solid #2c3e50;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-section img {
            width: 60px;
            height: auto;
        }

        .company-info {
            font-size: 12px;
            color: #555;
        }

        .company-info strong {
            color: #2c3e50;
        }

        /* Footer mejorado */
        .footer {
            position: fixed;
            bottom: -70px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 11px;
            border-top: 1px solid #eee;
            padding-top: 10px;
            background-color: #fff;
            color: #666;
        }

        /* Marca de agua mejorada */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            z-index: -1000;
        }

        .watermark img {
            opacity: 0.05;
            width: 400px;
        }

        .watermark p {
            text-align: center;
            font-size: 80px;
            color: rgba(44, 62, 80, 0.05);
            margin: 0;
            font-weight: bold;
        }

        /* Contenido principal mejorado */
        .container {
            margin-top: 20px;
            padding: 20px;
        }

        .report-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
        }

        .report-info h1 {
            font-size: 24px;
            color: #2c3e50;
            font-weight: 600;
            margin: 0 0 15px 0;
        }

        .report-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .report-info p {
            margin: 5px 0;
            font-size: 13px;
            color: #555;
        }

        /* Tabla mejorada */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        thead th {
            background-color: #2c3e50;
            color: white;
            padding: 12px 8px;
            font-weight: 600;
            text-align: left;
            border: none;
        }

        tbody td {
            padding: 10px 8px;
            border-bottom: 1px solid #e9ecef;
            color: #333;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr:hover {
            background-color: #f2f2f2;
        }

        /* Secciones finales mejoradas */
        .total-count {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 30px 0;
            text-align: right;
            font-size: 16px;
        }

        .notes {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
            page-break-inside: avoid;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            margin-left: auto;
            margin-bottom: 5px;
        }

        .signature p {
            margin: 3px 0;
            font-size: 12px;
            color: #555;
        }

        /* Numeración de páginas */
        .page-number {
            font-size: 11px;
            color: #666;
        }

        .page-number:before {
            content: "Página " counter(page) " de " counter(pages);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="logo-section">
                <img src="{{ public_path('dist/assets/images/logo1.png') }}" alt="Logo Gimnasio Urbano">
                
            </div>
        </div>
    </div>

    <!-- Marca de agua -->
    <div class="watermark">
        <p>GIMNASIO URBANO</p>
    </div>

    <!-- Contenido principal -->
    <div class="container">
        <div class="report-info">
            <h1>Reporte de Ingresos</h1>
            <div class="report-info-grid">
                <div>
                    <p><strong>Fecha de Generación:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
                    <p><strong>Contacto:</strong> Tel: 75983258 | info@gimnasiourbano.com</p>
                    <p><strong>Nit:</strong> 1234567890</p>
                    <p><strong>Sucursal:</strong> Gimnasio Urbano - Sacaba, Abra</p>
                </div>
                <div>
                    <p><strong>Usuario:</strong> {{ $usuarioNombre }}</p>
                    @if($fechaInicio && $fechaFin)
                        <p><strong>Período:</strong> {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
                    @else
                        <p><strong>Período:</strong> No especificado</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabla de datos -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Descuento</th>
                    <th>Subtotal</th>
                    <th>Vendedor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ingresos as $index => $ingreso)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($ingreso->fechaInscripcion)->format('d/m/Y') }}</td>
                        <td>{{ $ingreso->clienteNombre }}</td>
                        <td>{{ $ingreso->tipoProducto }}</td>
                        <td>{{ number_format($ingreso->precio, 2) }} BOB</td>
                        <td>{{ number_format($ingreso->descuento, 2) }} BOB</td>
                        <td>{{ number_format($ingreso->subtotal, 2) }} BOB</td>
                        <td>{{ $ingreso->vendedor }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total -->
        <div class="total-count">
            <strong>Total de Ingresos: {{ number_format($totalIngresos, 2) }} BOB</strong>
        </div>

        <!-- Notas -->
        <div class="notes">
            <strong>Notas:</strong> Este reporte muestra el detalle de ingresos por inscripciones realizadas en el período especificado. 
            Los montos incluyen impuestos y están expresados en Bolivianos (BOB).
        </div>

        <!-- Firma -->
        <div class="signature">
            <div class="signature-line"></div>
            <p><strong>Firma del Responsable</strong></p>
            <p>{{ $usuarioNombre }}</p>
            <p>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Footer -->
    
</body>
</html>