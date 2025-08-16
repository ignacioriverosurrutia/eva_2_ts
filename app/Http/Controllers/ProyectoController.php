<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProyectoController extends Controller
{
    //Listar todos los proyectos
    public function index()
    {
        $proyectos = Proyecto::all();
        return response()->json($proyectos);
    }

    //Crear un nuevo proyecto
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|string|max:255',
            'responsable' => 'required|string|max:255',
            'monto' => 'required|numeric'
        ]);

        $proyecto = Proyecto::create($data);
        return response()->json($proyecto, 201);
    }

    //Mostrar un proyecto por su ID
    public function show($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        return response()->json($proyecto);
    }

    //Actualizar un proyecto
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|string|max:255',
            'responsable' => 'required|string|max:255',
            'monto' => 'required|numeric'
        ]);

        $proyecto = Proyecto::findOrFail($id);
        $proyecto->update($data);
        return response()->json($proyecto);
    }

    //Eliminar un proyecto
    public function destroy($id)
    {
        Proyecto::destroy($id);
        return response()->json(['mensaje' => 'Proyecto eliminado correctamente']);
    }

    // Obtener el valor de la UF desde la API pública
    public function mostrarUF()
    {
        try {
            // Llama a la API pública de la UF
            $res = Http::timeout(8)->retry(2, 200)->get('https://mindicador.cl/api/uf');

            if ($res->failed()) {
                return response()->json(['message' => 'No se pudo obtener la UF'], 502);
            }

            $json  = $res->json();
            $valor = data_get($json, 'serie.0.valor');
            $fecha = data_get($json, 'serie.0.fecha');

            return response()->json([
                'uf'    => $valor,
                'fecha' => $fecha,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al consultar la UF'], 500);
        }
    }
}
