<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Servicio::all(),
            'message' => 'Listado de servicios obtenido exitosamente',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $servicio = Servicio::create($this->normalizarDatos($validated));

        return response()->json([
            'success' => true,
            'data' => $servicio,
            'message' => 'Servicio creado exitosamente',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $servicio = Servicio::with('actividades')->find($id);

        if (! $servicio) {
            return response()->json([
                'success' => false,
                'message' => 'Servicio no encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $servicio,
            'message' => 'Servicio obtenido exitosamente',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $servicio = Servicio::find($id);

        if (! $servicio) {
            return response()->json([
                'success' => false,
                'message' => 'Servicio no encontrado',
            ], 404);
        }

        $validated = $request->validate($this->rules());

        $servicio->update($this->normalizarDatos($validated));

        return response()->json([
            'success' => true,
            'data' => $servicio,
            'message' => 'Servicio actualizado exitosamente',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $servicio = Servicio::find($id);

        if (! $servicio) {
            return response()->json([
                'success' => false,
                'message' => 'Servicio no encontrado',
            ], 404);
        }

        $servicio->delete();

        return response()->json([
            'success' => true,
            'message' => 'Servicio eliminado exitosamente',
        ]);
    }

    private function rules(): array
    {
        return [
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'imagen_url' => 'nullable|string|max:255',
            'imagenUrl' => 'nullable|string|max:255',
        ];
    }

    private function normalizarDatos(array $datos): array
    {
        if (array_key_exists('imagenUrl', $datos)) {
            $datos['imagen_url'] = $datos['imagenUrl'];
            unset($datos['imagenUrl']);
        }

        return $datos;
    }
}
