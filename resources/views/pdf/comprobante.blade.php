<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            width: 100%;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
        }
        .content {
            margin: 20px 0;
        }
        .content h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
        }
        .footer p {
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Comprobante de Pago</h1>
            <p>{{ now()->format('d-m-Y H:i:s') }}</p>
        </div>
        <div class="content">
            <h2>Detalles del Cliente</h2>
            <table class="table">
                <tr>
                    <th>Nombre:</th>
                    <td>{{ $cliente->nombre }} {{ $cliente->primerApellido }} {{ $cliente->segundoApellido }}</td>
                </tr>
                <tr>
                    <th>Correo Electrónico:</th>
                    <td>{{ $cliente->usuario->email }}</td>
                </tr>
                <tr>
                    <th>Fecha de Nacimiento:</th>
                    <td>{{ \Carbon\Carbon::parse($cliente->fechaNacimiento)->format('d-m-Y') }}</td>
                </tr>
            </table>

            <h2>Detalles de la Membresía</h2>
            <table class="table">
                <tr>
                    <th>Plan:</th>
                    <td>{{ $membresia->nombre }}</td>
                </tr>
                <tr>
                    <th>Duración:</th>
                    <td>{{ $membresia->duracion }} días</td>
                </tr>
                <tr>
                    <th>Fecha de Inicio:</th>
                    <td>{{ \Carbon\Carbon::parse($fechaInicio)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Fecha de Fin:</th>
                    <td>{{ \Carbon\Carbon::parse($fechaFin)->format('d-m-Y') }}</td>
                </tr>
            </table>

            <h2>Detalles del Pago</h2>
            <table class="table">
                <tr>
                    <th>Monto Pagado:</th>
                    <td>${{ number_format($montoPago, 2) }}</td>
                </tr>
                <tr>
                    <th>Fecha del Pago:</th>
                    <td>{{ \Carbon\Carbon::parse($fechaPago)->format('d-m-Y H:i:s') }}</td>
                </tr>
                <tr>
                    <th>Método de Pago:</th>
                    <td>Contado</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Gracias por tu pago. Si tienes alguna duda, por favor contacta a nuestro equipo de soporte.</p>
        </div>
    </div>
</body>
</html>
