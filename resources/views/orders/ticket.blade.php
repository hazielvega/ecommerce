<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Compra #{{ $order->id }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.5;
        }

        .page-break {
            page-break-after: always;
        }

        .ticket {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4a5568;
            padding-bottom: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            color: #718096;
        }

        h1,
        h2,
        h3,
        h4 {
            color: #2d3748;
            margin-top: 0;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 20px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        h3 {
            font-size: 18px;
            color: #4a5568;
            margin-bottom: 10px;
        }

        h4 {
            font-size: 16px;
            margin-bottom: 15px;
            color: #4a5568;
        }

        .info-box {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f8fafc;
            border-radius: 6px;
            border-left: 4px solid #4299e1;
        }

        .info-item {
            margin-bottom: 8px;
            display: flex;
        }

        .info-label {
            font-weight: 500;
            min-width: 150px;
            color: #4a5568;
        }

        .info-value {
            flex: 1;
        }

        /* Estilos de la tabla */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 14px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        .product-table thead tr {
            background-color: #4299e1;
            color: white;
            text-align: left;
        }

        .product-table th,
        .product-table td {
            padding: 12px 15px;
        }

        .product-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .product-table tbody tr:nth-of-type(even) {
            background-color: #f8fafc;
        }

        .product-table tbody tr:last-of-type {
            border-bottom: 2px solid #4299e1;
        }

        .product-table tbody tr:hover {
            background-color: #ebf8ff;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: 700;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #718096;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 12px;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 4px;
        }

        .badge-primary {
            color: #fff;
            background-color: #4299e1;
        }

        .feature-badge {
            display: inline-block;
            margin-right: 5px;
            margin-bottom: 5px;
            padding: 3px 8px;
            font-size: 12px;
            background-color: #e2e8f0;
            border-radius: 4px;
            color: #4a5568;
        }
    </style>
</head>

<body>
    <div class="ticket">
        <!-- Encabezado -->
        <div class="header">
            <div class="logo">ECOMMERCE STORE</div>
            <div class="subtitle">Seminario Técnico Profesional</div>
            <h1>COMPROBANTE DE COMPRA</h1>
            <h4>Orden #{{ $order->id }} - {{ $order->created_at->format('d/m/Y H:i') }}</h4>
        </div>

        <!-- Información de la empresa -->
        <div class="info-box">
            <h3>Información de la Tienda</h3>
            <div class="info-item">
                <span class="info-label">Nombre:</span>
                <span class="info-value">Ecommerce Store</span>
            </div>
            <div class="info-item">
                <span class="info-label">Teléfono:</span>
                <span class="info-value">123456789</span>
            </div>
            <div class="info-item">
                <span class="info-label">Email:</span>
                <span class="info-value">hazielvega63@gmail.com</span>
            </div>
        </div>

        <!-- Información del cliente -->
        <div class="info-box">
            <h3>Datos del Cliente</h3>
            <div class="info-item">
                <span class="info-label">Nombre:</span>
                <span class="info-value">{{ $receiver->name }} {{ $receiver->last_name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Documento:</span>
                <span class="info-value">{{ $receiver->document_number }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Teléfono:</span>
                <span class="info-value">{{ $receiver->phone }}</span>
            </div>
        </div>

        <!-- Direcciones -->
        <div class="info-box">
            <h3>Direcciones</h3>
            <div class="info-item">
                <span class="info-label">Envío:</span>
                <span class="info-value">
                    {{ $shipping_address->calle }}, {{ $shipping_address->numero }},
                    {{ $shipping_address->ciudad }}, {{ $shipping_address->provincia }}
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Facturación:</span>
                <span class="info-value">
                    {{ $billing_address->calle }}, {{ $billing_address->numero }},
                    {{ $billing_address->ciudad }}, {{ $billing_address->provincia }}
                </span>
            </div>
        </div>

        <!-- Método de pago -->
        <div class="info-box">
            <h3>Información de Pago</h3>
            <div class="info-item">
                <span class="info-label">Método:</span>
                <span class="info-value">Tarjeta de crédito</span>
            </div>
            <div class="info-item">
                <span class="info-label">Número de tarjeta:</span>
                <span class="info-value">**** **** **** {{ substr($order->card_number, -4) }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Estado:</span>
                <span class="info-value">
                    <span class="badge badge-primary">Completado</span>
                </span>
            </div>
        </div>

        <div class="page-break"></div>

        <!-- Productos -->
        <h2>Detalle de Productos</h2>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th class="text-right">Precio Unitario</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>
                            <div style="font-weight: 500;">{{ $item->variant->product->name }}</div>
                            @if ($item->variant->features->count())
                                <div style="margin-top: 5px;">
                                    @foreach ($item->variant->features as $feature)
                                        <span class="feature-badge">{{ $feature->description }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-right">${{ number_format($item->price, 2) }}</td>
                        <td class="text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right text-bold">Subtotal:</td>
                    <td class="text-right text-bold">${{ number_format($order->items->sum('subtotal'), 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right text-bold">Envío:</td>
                    <td class="text-right text-bold">$500.00</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right text-bold">Total:</td>
                    <td class="text-right text-bold">${{ number_format($order->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>¡Gracias por su compra!</p>
            <p>Para consultas o soporte, contacte a: soporte@ecommerce.com</p>
            <p>Este comprobante es válido como factura electrónica</p>
            <p>Fecha de emisión: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>

</html>
