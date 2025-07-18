<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Inscripción - Gimnasio Urbano</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fdfdfd;
            color: #333;
            line-height: 1.5;
        }

        .container {
            width: 90%;
            max-width: 700px;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #2c3e50;
            color: #ffffff;
            padding: 15px;
            text-align: center;
            border-bottom: 4px solid #e67e22;
        }

        .header img {
            max-width: 50px;
            margin-bottom: 5px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
        }

        .business-info {
            padding: 10px 20px;
            background: #f4f4f4;
            text-align: center;
            font-size: 14px;
            border-bottom: 2px solid #ddd;
        }

        .content {
            padding: 20px;
        }

        .customer-info,
        .invoice-summary {
            margin-bottom: 10px;
        }

        .customer-info p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 14px;
        }

        table th,
        table td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #2c3e50;
            color: #ffffff;
        }

        .total {
            margin-top: 10px;
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            color: #e67e22;
        }

        .qr-code {
            text-align: center;
            margin: 15px 0;
        }

        .qr-code img {
            width: 100px;
            margin: 10px auto;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 8px;
            font-size: 12px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #555;
            padding: 10px 20px;
            border-top: 2px solid #ddd;
            background: #f4f4f4;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="{{ public_path('dist/assets/images/logo1.png') }}" alt="Logo">
            <h1>Gimnasio Urbano</h1>
        </div>

        <!-- Business Info -->
        <div class="business-info">
            <p><strong>Dirección:</strong> El Abra, Sacaba, Cochabamba</p>
            <p><strong>Teléfono:</strong> 75983258 | <strong>Email:</strong> info@gimnasiourbano.com</p>
            <p><strong>NIT:</strong> 1234567890</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Customer Info -->
            <div class="customer-info">
                <h3 style="color: #2c3e50;">Comprobante de Inscripción</h3>
                <p><strong>Cliente:</strong> {{ $data['cliente']['nombreCompleto'] }}</p>
                <p><strong>CI/NIT:</strong> {{ $data['cliente']['ci'] ?? 'N/A' }}</p>
                <p><strong>Nº Comprobante:</strong> {{ str_pad($data['numeroComprobante'] ?? rand(1000, 9999), 8, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Fecha:</strong> {{ $data['fecha'] }}</p>
            </div>

            <!-- Table -->
            <table>
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th>Precio Unit.</th>
                        <th>Desc.</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['productos'] as $producto)
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

            <!-- Total -->
            <div class="total">
                Total Pagado: Bs{{ number_format($data['totalPago'], 2) }}
            </div>

            <!-- QR Code -->
            <div class="qr-code">
                <h4>Escanea el código QR:</h4>
                <img src="data:image/png;base64,{{ $qrCode }}" alt="Código QR">
            </div>

            <!-- Signatures -->
            <div class="signatures">
                <div class="signature-box">Firma del Cliente</div>
                <div class="signature-box">Firma del Responsable</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Gracias por confiar en Gimnasio Urbano.<br>Visítenos en www.gimnasiourbano.com</p>
        </div>
    </div>
</body>

</html>
