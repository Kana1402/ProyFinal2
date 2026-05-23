<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\ActividadProgramada;

class ReservaController extends Controller
{
    // LISTAR TODAS
    public function index()
    {
        $reservas = Reserva::with([
            'usuario',
            'actividad'
        ])->get();

        return response()->json($reservas);
    }

    // LISTAR POR USUARIO
    public function listarPorUsuario($usuarioId)
    {
        $reservas = Reserva::where('usuario_id', $usuarioId)
            ->with(['actividad'])
            ->get();

        return response()->json($reservas);
    }

    // LISTAR POR ACTIVIDAD
    public function listarPorActividad($actividadId)
    {
        $reservas = Reserva::where('actividad_id', $actividadId)
            ->with(['usuario'])
            ->get();

        return response()->json($reservas);
    }

    // OBTENER POR ID
    public function show($id)
    {
        $reserva = Reserva::with([
            'usuario',
            'actividad'
        ])->find($id);

        if (!$reserva) {
            return response()->json([
                'message' => 'Reserva no encontrada'
            ], 404);
        }

        return response()->json($reserva);
    }

    // CREAR RESERVA
   public function store(Request $request)
{
    // 1. VALIDACIÓN OBLIGATORIA
    $request->validate([
        'actividad_id' => 'required|exists:actividades_programadas,id',
        'cantidad_personas' => 'required|integer|min:1',
        'notas' => 'nullable|string'
    ]);

    // 2. BUSCAR ACTIVIDAD
    $actividad = ActividadProgramada::find($request->actividad_id);

    if (!$actividad) {
        return response()->json([
            'message' => 'Actividad no encontrada'
        ], 404);
    }

    // 3. PROTEGER CUPOS (EVITA 500)
    if ($actividad->cupo_disponible === null) {
        return response()->json([
            'message' => 'Actividad sin cupos definidos'
        ], 400);
    }

    if ((int)$actividad->cupo_disponible < (int)$request->cantidad_personas) {
        return response()->json([
            'message' => 'No hay cupos suficientes'
        ], 400);
    }

    // 4. CREAR RESERVA (usuario desde token)
    $reserva = Reserva::create([
        'usuario_id' => $request->user()->id,
        'actividad_id' => $request->actividad_id,
        'cantidad_personas' => $request->cantidad_personas,
        'estado' => 'PENDIENTE',
        'notas' => $request->notas
    ]);

    // 5. RESTAR CUPOS
    $actividad->cupo_disponible -= (int)$request->cantidad_personas;
    $actividad->save();

    return response()->json([
        'success' => true,
        'data' => $reserva
    ], 201);
}

    // CAMBIAR ESTADO
    public function cambiarEstado(Request $request, $id)
    {
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json([
                'message' => 'Reserva no encontrada'
            ], 404);
        }

        $reserva->estado = $request->estado;
        $reserva->save();

        return response()->json($reserva);
    }

    // ELIMINAR
    public function destroy($id)
    {
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json([
                'message' => 'Reserva no encontrada'
            ], 404);
        }

        $reserva->delete();

        return response()->json([
            'message' => 'Reserva eliminada'
        ]);
    }

    public function update(Request $request, $id)
{
    $reserva = Reserva::find($id);

    if (!$reserva) {
        return response()->json([
            'message' => 'Reserva no encontrada'
        ], 404);
    }

    $request->validate([
        'actividad_id' => 'sometimes|exists:actividades_programadas,id',
        'cantidad_personas' => 'sometimes|integer|min:1',
        'notas' => 'nullable|string',
        'estado' => 'nullable|string'
    ]);

    // actualizar campos si vienen
    if ($request->has('actividad_id')) {
        $reserva->actividad_id = $request->actividad_id;
    }

    if ($request->has('cantidad_personas')) {
        $reserva->cantidad_personas = $request->cantidad_personas;
    }

    if ($request->has('notas')) {
        $reserva->notas = $request->notas;
    }

    if ($request->has('estado')) {
        $reserva->estado = $request->estado;
    }

    $reserva->save();

    return response()->json([
        'success' => true,
        'data' => $reserva
    ]);
}
}