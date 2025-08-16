<!-- resources/views/proyectos/delete.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Eliminar Proyecto</title>
</head>
<body>
    <h1>¿Seguro que deseas eliminar este proyecto?</h1>
    <form method="POST" action="/proyectos/{{ $proyecto['id'] }}">
        @csrf
        @method('DELETE')
        <button type="submit">Eliminar</button>
    </form>
    <a href="/proyectos">Cancelar</a>
</body>
</html>