<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Inscripción - Gimnasio Urbano</title>
    <style>
        /* Estilos mejorados para un diseño más profesional */
        body {
            font-family: 'Helvetica', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            border-bottom: 2px solid #2c3e50;
        }

        .header img {
            max-width: 50px;
            margin-bottom: 15px;
        }

        .header h2 {
            color: #2c3e50;
            margin: 10px 0;
            font-size: 24px;
        }

        .business-info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .content {
            margin: 0 30px;
        }

        .customer-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
        }

        table, th, td {
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #2c3e50;
            color: white;
            padding: 12px 8px;
            font-weight: normal;
        }

        td {
            padding: 10px 8px;
            color: #555;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .total {
            text-align: right;
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #dee2e6;
            color: #666;
            font-size: 14px;
        }

        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('dist/assets/images/logo1.png') }}" alt="Gimnasio Urbano Logo">
        <h2>GIMNASIO URBANO</h2>
    </div>

    <div class="business-info">
        <p>
            <strong>Dirección:</strong> El Abra, Sacaba, Cochabamba<br>
            <strong>Teléfono:</strong> 75983258<br>
            <strong>Email:</strong> info@gimnasiourbano.com<br>
            <strong>NIT:</strong> 1234567890
        </p>
    </div>

    <div class="content">
        <div class="customer-info">
            <h3 style="color: #2c3e50; margin: 0 0 10px 0;">COMPROBANTE DE INSCRIPCIÓN</h3>
            <div class="invoice-details">
                <div>
                    <p><strong>Cliente:</strong> {{ $data['cliente']['nombreCompleto'] }}<br>
                    <strong>CI/NIT:</strong> {{ $data['cliente']['ci'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p><strong>Nº Comprobante:</strong> {{ str_pad($data['numeroComprobante'] ?? rand(1000, 9999), 8, '0', STR_PAD_LEFT) }}<br>
                    <strong>Fecha:</strong> {{ $data['fecha'] }}</p>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>DESCRIPCIÓN</th>
                    <th>TIPO</th>
                    <th>PRECIO UNIT.</th>
                    <th>DESC.</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['productos'] as $producto)
                <tr>
                    <td>{{ $producto['nombre'] }}</td>
                    <td>{{ ucfirst($producto['tipoProducto']) }}</td>
                    <td style="text-align: right;">Bs{{ number_format($producto['precio'], 2) }}</td>
                    <td style="text-align: center;">{{ $producto['descuento'] }}%</td>
                    <td style="text-align: right;">Bs{{ number_format($producto['precioFinal'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <h3 style="color: #2c3e50; margin: 0;">Total Pagado: Bs{{ number_format($data['totalPago'], 2) }}</h3>
        </div>

        <div class="qr-code">
            <!-- Aquí puedes agregar un código QR si lo deseas -->
        </div>
    </div>

    <div class="footer">
        <p>¡Gracias por confiar en Gimnasio Urbano!<br>
        Este comprobante es un documento válido para su inscripción.<br>
        Para más información, visítenos en www.gimnasiourbano.com</p>
    </div>
</body>
</html>