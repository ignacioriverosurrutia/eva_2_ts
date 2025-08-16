<!-- resources/views/proyectos/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Listado de Proyectos</title>
</head>
<body>

    {{-- Mostrar el valor de la UF al inicio usando el componente --}}
    <x-uf />

    {{-- Mensajes de éxito o error --}}
    @if(session('mensaje'))
        <p style="color: green">{{ session('mensaje') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    <h1>Todos los Proyectos</h1>

    <a href="/proyectos/create">+ Crear nuevo proyecto</a>

    <ul>
        @foreach ($proyectos as $proyecto)
            <li>
                <strong>{{ $proyecto['nombre'] }}</strong> ({{ $proyecto['estado'] }})<br>
                Responsable: {{ $proyecto['responsable'] }}<br>
                Monto: ${{ number_format($proyecto['monto'], 0, ',', '.') }}<br>
                [<a href="/proyectos/{{ $proyecto['id'] }}">Ver</a>]
                [<a href="/proyectos/{{ $proyecto['id'] }}/edit">Editar</a>]
                <form action="/proyectos/{{ $proyecto['id'] }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar</button>
                </form>
            </li>
            <hr>
        @endforeach
    </ul>

</body>
</html>