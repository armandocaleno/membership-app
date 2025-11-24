<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Suscripcion activada!</h1>
    <p>{{ $suscription->customer->name }}, su suscripción {{ $suscription->number }} al plan {{ $suscription->plan->name }} ha sido activada correctamente.</p>
    <p>Su suscripción caduca el {{ $suscription->end_date }}</p>
</body>
</html>