<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ingresos</title>
    <style>
        @page {
            margin: 90px 25px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 16px;
            margin: 0;
            padding: 0;
            background-color: #fff;
            position: relative;
        }

        /* Estilos para elementos que se repiten en cada página */
        .header {
            position: fixed;
            top: -60px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: left;
            padding: 20px;
        }

        .header img {
            width: 50px;
        }

        .footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 12px;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }

        /* Marca de agua en cada página */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1000;
        }

        .watermark img {
            opacity: 0.1;
            width: 300px;
        }

        .watermark p {
            text-align: center;
            font-size: 100px;
            color: rgba(0, 0, 0, 0.05);
            margin: 0;
        }

        /* Contenido principal */
        .container {
            margin-top: 40px;
            padding: 20px;
        }

        .report-info {
            text-align: right;
            margin-top: -80px;
            margin-bottom: 30px;
        }

        .report-info h1 {
            font-size: 24px;
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

        /* Numeración de páginas */
        .pagenum:before {
            content: counter(page);
        }

        .pagecount:before {
            content: counter(pages);
        }

        /* Secciones finales */
        .total-count, .notes, .signature {
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .signature {
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <!-- Header fijo para todas las páginas -->
    <div class="header">
        <img src="{{ public_path('dist/assets/images/logo1.png') }}" alt="Logo">
    </div>

    

    <!-- Marca de agua fija para todas las páginas -->
    <div class="watermark">
        <img src="{{ public_path('dist/assets/images/logo1.png') }}" alt="Logo" />
        <p>Gimnasio Urbano</p>
    </div>

    <!-- Contenido principal -->
    <div class="container">
        <!-- Información del reporte -->
        <div class="report-info">
            <h1>Reporte de Ingresos</h1>
            <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
            <p>Sucursal: Gimnasio Urbano - Sacaba, Abra</p>
            <p>Usuario: {{ $usuarioNombre }}</p>

            @if($fechaInicio && $fechaFin)
                <p>Desde: {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} hasta {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
            @else
                <p>Rango de fechas no especificado</p>
            @endif
        </div>

        <!-- Tabla de datos -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha de Inscripción</th>
                    <th>Cliente</th>
                    <th>Tipo de Producto</th>
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

        <!-- Secciones finales -->
        <div class="total-count">
            <p><strong>Total de Ingresos:</strong> {{ number_format($totalIngresos, 2) }} BOB</p>
        </div>

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
</body>
</html>