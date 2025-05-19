<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Respuesta a tu Cotización</title>
</head>
<body>
    <h2>Estimado {{ $cotizacion->cliente->nombre }}</h2>
    <p>Hemos respondido a tu cotización con el ID: <strong>{{ $cotizacion->id_cotizacion }}</strong></p>
    <p><strong>Comentario:</strong> {{ $mensaje }}</p>
    <p>Gracias por confiar en nosotros, estamos a tu disposición para cualquier consulta adicional.</p>
    <p>Atentamente,<br>Carpintería</p>
</body>
</html>
