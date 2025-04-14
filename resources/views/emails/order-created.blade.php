<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orden de compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .email-header {
            background-color: #4a4cff;
            color: #ffffff;
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .email-body {
            padding: 20px;
            font-size: 16px;
            line-height: 1.6;
        }

        .email-body h1 {
            font-size: 24px;
            color: #4a4cff;
            margin-bottom: 10px;
        }

        .email-body p {
            margin: 10px 0;
        }

        .email-footer {
            background-color: #f9f9f9;
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #666;
        }

        .email-footer a {
            color: #4a4cff;
            text-decoration: none;
        }

        .order-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }

        .order-details p {
            margin: 5px 0;
        }

        .btnn {
            display: inline-block;
            background-color: #4a4cff;
            color: #ffffff !important;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 15px;
            text-align: center;
        }

        .btnn:hover {
            background-color: #3b3bcc;
            color: #ffffff !important;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            ¡Gracias por tu compra!
        </div>
        <div class="email-body">
            <h1>
                Hola {{ $order->receiver->name }}
            </h1>
            <p>
                Gracias por confiar en nosotros. Tu compra ha sido realizada con éxito.
            </p>
            <div class="order-details">
                <h3>Detalles de tu orden:</h3>
                <p><strong>Número de orden:</strong> {{ $order->id }}</p>
            </div>
            <p>
                Te informaremos cuando el pedido sea enviado.
            </p>
            <a href="{{ route('orders.show', $order->id) }}" class="btnn">Ver detalle de mi pedido</a>

            <div>
                <h3>Información de contacto:</h3>
                <p><strong>Empresa:</strong> Ecommerce STP</p>
                <p><strong>Teléfono:</strong> 555-555-5555</p>
            </div>
            <a href="{{ route('welcome.index') }}" class="btnn">Volver a la tienda</a>
        </div>
        <div class="email-footer">
            Saludos,<br>
            El equipo de <strong>Ecommerce STP</strong>.<br>
        </div>
    </div>
</body>

</html>
