<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ticket de compra</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-after: always;
        }

        .ticket {
            width: 100%;
            margin: 20px auto;
            padding: 20px;
        }

        h1,
        h2,
        h3,
        h4 {
            text-align: center;
            margin-bottom: 10px;
        }

        .info {
            margin-bottom: 20px;
        }

        .info div {
            margin-bottom: 5px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
        }

        /* Estilos de la tabla de productos */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .product-table th,
        .product-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .product-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="ticket">
        <h4>
            Número de orden: {{ $order->id }}
        </h4>

        {{-- Información de la empresa --}}
        <div class="info">
            <h3>
                Información de la empresa:
            </h3>

            <div>
                Nombre: Ecommerce
            </div>

            <div>
                Seminario Técnico Profesional
            </div>

            <div>
                Teléfono: 123456789
            </div>

            <div>
                Email: hazielvega63@gmail.com
            </div>
        </div>

        {{-- Información del cliente --}}
        <div class="info">
            <h3>
                Datos del destinatario:
            </h3>

            <div>
                Nombre:
                {{ $receiver->name . ' ' . $receiver->last_name }}
            </div>

            <div>
                Documento: {{ $receiver->document_number }}
            </div>

            {{-- Dirección de envío --}}
            <div>
                Dirección de envío:
                {{ $shipping_address->calle . ', ' . $shipping_address->numero . ', ' . $shipping_address->provincia . ', ' . $shipping_address->ciudad }}
            </div>

            {{-- Dirección de facturación --}}
            <div>
                Dirección de facturación:
                {{ $billing_address->calle . ', ' . $billing_address->numero . ', ' . $billing_address->provincia . ', ' . $billing_address->ciudad }}
            </div>

            <div>
                Teléfono: {{ $receiver->phone }}
            </div>

            {{-- Forma de pago --}}
            <div>
                Forma de pago: Tarjeta de crédito
            </div>
            {{-- Numero de tarjeta	 --}}
            <div>
                Numero de tarjeta: {{ $order->card_number }}
            </div>
        </div>

        <div class="page-break"></div>
        {{-- @dump($order->items) --}}
        {{-- Lista de productos de la orden --}}
        <div class="product-list">
            <h3>Productos de la Orden:</h3>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            {{-- Nombre del producto y caracteristicas de la variante --}}
                            <td>
                                <p>
                                    {{ $item->variant->product->name }}
                                    {{-- Mostrar las caracteristicas de la variante --}}
                                    @foreach ($item->variant->features as $feature)
                                        {{ $feature->description }}
                                    @endforeach
                                </p>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ $item->price }}</td>
                        </tr>
                    @endforeach
                    {{-- envio --}}
                    <tr>
                        <td colspan="2">Envio:</td>
                        <td>$500</td>
                    </tr>
                    {{-- total --}}
                    <tr>
                        <td colspan="2">Total:</td>
                        <td>${{ $order->total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>


        {{-- Footer --}}
        <div class="footer">
            ¡Gracias por tu compra!
        </div>
    </div>

</body>

</html>
