<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Bienvenido a nuestro sitio!</title>
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
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #4a90e2;
            color: #ffffff;
            text-align: center;
            padding: 20px;
            font-size: 24px;
        }
        .email-body {
            padding: 20px;
            font-size: 16px;
            line-height: 1.6;
        }
        .email-footer {
            text-align: center;
            padding: 10px;
            background: #f9f9f9;
            font-size: 14px;
            color: #666;
        }
        .email-footer a {
            color: #4a90e2;
            text-decoration: none;
        }
        .btnn {
            display: inline-block;
            background-color: #4a90e2;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            ¡Bienvenido a EcommerceSTP!
        </div>
        <div class="email-body">
            <p>¡Hola, {{ $user->name }}!</p>
            <p>
                Gracias por registrarte en nuestro sitio. Estamos emocionados de tenerte con nosotros.
            </p>
            <p>
                Si tienes alguna pregunta, no dudes en contactarnos.
            </p>
            <p>
                <a href="{{ route('welcome.index') }}" class="btnn">Visita nuestro sitio</a>
            </p>
        </div>
        <div class="email-footer">
            Saludos,<br>
            El equipo de EcommerceSTP<br>
            {{-- <a href="{{ route('contact') }}">Contáctanos</a> --}}
        </div>
    </div>
</body>
</html>

