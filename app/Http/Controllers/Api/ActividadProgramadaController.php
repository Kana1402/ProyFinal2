<?php

namespace App\Http\Controllers\Api;

use App\Enums\EstadoActividad;
use App\Http\Controllers\Controller;
use App\Models\ActividadProgramada;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class ActividadProgramadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $actividades = ActividadProgramada::with('servicio')
            ->orderBy('fecha_hora')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $actividades,
            'message' => 'Listado de actividades programadas obtenido exitosamente',
        ]);
    }

    public function porServicio(string $servicioId): JsonResponse
    {
        $actividades = ActividadProgramada::with('servicio')
            ->where('servicio_id', $servicioId)
            ->where('fecha_hora', '>=', now())
            ->orderBy('fecha_hora')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $actividades,
            'message' => 'Listado de actividades por servicio obtenido exitosamente',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'fecha_hora' => 'required|date',
            'cupo_maximo' => 'required|integer|min:1',
            'estado' => ['nullable', new Enum(EstadoActividad::class)],
        ]);

        $validated['cupo_disponible'] = $validated['cupo_maximo'];
        $validated['estado'] = $validated['estado'] ?? EstadoActividad::PROGRAMADA->value;

        $actividad = ActividadProgramada::create($validated);

        return response()->json([
            'success' => true,
            'data' => $actividad->load('servicio'),
            'message' => 'Actividad programada creada exitosamente',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $actividad = ActividadProgramada::with('servicio')->find($id);

        if (! $actividad) {
            return response()->json([
                'success' => false,
                'message' => 'Actividad programada no encontrada',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $actividad,
            'message' => 'Actividad programada obtenida exitosamente',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $actividad = ActividadProgramada::find($id);

        if (! $actividad) {
            return response()->json([
                'success' => false,
                'message' => 'Actividad programada no encontrada',
            ], 404);
        }

        $validated = $request->validate([
            'servicio_id' => 'sometimes|exists:servicios,id',
            'fecha_hora' => 'required|date',
            'cupo_maximo' => 'required|integer|min:1',
            'estado' => ['required', new Enum(EstadoActividad::class)],
        ]);

        $reservados = $actividad->cupo_maximo - $actividad->cupo_disponible;
        $nuevoCupoMaximo = $validated['cupo_maximo'];

        if ($nuevoCupoMaximo < $reservados) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes reducir cupos por debajo de los ya reservados',
            ], 422);
        }

        $validated['cupo_disponible'] = $nuevoCupoMaximo - $reservados;

        $actividad->update($validated);

        return response()->json([
            'success' => true,
            'data' => $actividad->load('servicio'),
            'message' => 'Actividad programada actualizada exitosamente',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $actividad = ActividadProgramada::find($id);

        if (! $actividad) {
            return response()->json([
                'success' => false,
                'message' => 'Actividad programada no encontrada',
            ], 404);
        }

        $actividad->delete();

        return response()->json([
            'success' => true,
            'message' => 'Actividad programada eliminada exitosamente',
        ]);
    }
}
