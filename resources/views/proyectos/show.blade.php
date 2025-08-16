<!-- resources/views/proyectos/show.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Detalle del Proyecto</title>
</head>
<body>
    <h1>Proyecto: {{ $proyecto['nombre'] }}</h1>
    <p><strong>Fecha de Inicio:</strong> {{ $proyecto['fecha_inicio'] }}</p>
    <p><strong>Estado:</strong> {{ $proyecto['estado'] }}</p>
    <p><strong>Responsable:</strong> {{ $proyecto['responsable'] }}</p>
    <p><strong>Monto:</strong> ${{ $proyecto['monto'] }}</p>
    <a href="/proyectos">Volver al listado</a>
</body>
</html>