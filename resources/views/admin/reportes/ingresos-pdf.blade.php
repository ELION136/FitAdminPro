<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ingresos</title>
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
        <!-- Header con el logo -->
        <div class="header">
            <img src="{{ public_path('dist/assets/images/logo1.png') }}" alt="Logo">
        </div>

        <!-- Información del reporte -->
        <div class="report-info">
            <h1>Reporte de Ingresos</h1>
            <h2 class="fw-bold text-warning">Gimnasio Urbano</h2>
            <p>Sucursal: Sacaba, Abra</p>
            <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
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
                @foreach ($ingresos as $ingreso)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($ingreso->fechaInscripcion)->format('d/m/Y') }}</td>
                        <td>{{ $ingreso->clienteNombre }}</td>
                        <td>{{ $ingreso->tipoProducto }}</td>
                        <td>{{ number_format($ingreso->precio, 2) }}</td>
                        <td>{{ number_format($ingreso->descuento, 2) }}</td>
                        <td>{{ number_format($ingreso->subtotal, 2) }}</td>
                        <td>{{ $ingreso->vendedor }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3>Total de Ingresos: {{ number_format($totalIngresos, 2) }} BOB</h3>
    </div>

    <!-- Footer con número de página -->
    <footer>
        <p>&copy; {{ date('Y') }} Nombre de la Empresa - Sucursal | Página <span class="pagenum"></span></p>
    </footer>

    <!-- Marca de agua -->
    <div class="watermark">
        <img src="{{ public_path('dist/assets/images/logo1.png') }}" alt="Logo" style="opacity: 0.1; width: 300px; display: block; margin: 0 auto;" />
        <p>FitAdminPro</p>
    </div>
</body>
</html>
