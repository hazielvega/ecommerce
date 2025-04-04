<!DOCTYPE html>
<html>
<head>
    <title>Pago con Mercado Pago</title>
</head>
<body>
    <h1>Checkout de prueba</h1>
    <button onclick="window.location.href='{{ route('payment.checkout') }}'">
        Pagar con Mercado Pago
    </button>
</body>
</html>