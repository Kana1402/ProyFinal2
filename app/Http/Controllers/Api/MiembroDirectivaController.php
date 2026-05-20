<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MiembroDirectiva;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MiembroDirectivaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $miembros = MiembroDirectiva::orderBy('orden_prioridad')->get();

        return response()->json([
            'success' => true,
            'data' => $miembros,
            'message' => 'Listado de miembros de directiva obtenido exitosamente',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'puesto' => 'required|string|max:100',
            'biografia' => 'nullable|string',
            'foto_url' => 'nullable|string|max:255',
            'fotoUrl' => 'nullable|string|max:255',
            'orden_prioridad' => 'nullable|integer',
        ]);

        $miembro = MiembroDirectiva::create($this->normalizarDatos($validated));

        return response()->json([
            'success' => true,
            'data' => $miembro,
            'message' => 'Miembro de directiva creado exitosamente',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $miembro = MiembroDirectiva::find($id);

        if (! $miembro) {
            return response()->json([
                'success' => false,
                'message' => 'Miembro de directiva no encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $miembro,
            'message' => 'Miembro de directiva obtenido exitosamente',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $miembro = MiembroDirectiva::find($id);

        if (! $miembro) {
            return response()->json([
                'success' => false,
                'message' => 'Miembro de directiva no encontrado',
            ], 404);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'puesto' => 'required|string|max:100',
            'biografia' => 'nullable|string',
            'foto_url' => 'nullable|string|max:255',
            'fotoUrl' => 'nullable|string|max:255',
            'orden_prioridad' => 'nullable|integer',
        ]);

        $miembro->update($this->normalizarDatos($validated));

        return response()->json([
            'success' => true,
            'data' => $miembro,
            'message' => 'Miembro de directiva actualizado exitosamente',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $miembro = MiembroDirectiva::find($id);

        if (! $miembro) {
            return response()->json([
                'success' => false,
                'message' => 'Miembro de directiva no encontrado',
            ], 404);
        }

        $miembro->delete();

        return response()->json([
            'success' => true,
            'message' => 'Miembro de directiva eliminado exitosamente',
        ]);
    }

    private function normalizarDatos(array $datos): array
    {
        if (array_key_exists('fotoUrl', $datos)) {
            $datos['foto_url'] = $datos['fotoUrl'];
            unset($datos['fotoUrl']);
        }

        return $datos;
    }
}
