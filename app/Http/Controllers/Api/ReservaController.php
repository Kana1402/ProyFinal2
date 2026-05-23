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
            'actividad.servicio'
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
    $rules = [
        'actividad_id' => 'required|exists:actividades_programadas,id',
        'cantidad_personas' => 'required|integer|min:1',
        'notas' => 'nullable|string'
    ];

    if ($request->user() && $request->user()->role->value === 'ADMIN') {
        $rules['usuario_id'] = 'required|exists:usuarios,id';
        $rules['estado'] = 'nullable|string';
    }

    $request->validate($rules);

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

    if ($actividad->estado === \App\Enums\EstadoActividad::COMPLETA || $actividad->cupo_disponible <= 0) {
        return response()->json([
            'message' => 'Esta actividad ya está completa, no puedes reservarla.'
        ], 400);
    }

    if ((int)$actividad->cupo_disponible < (int)$request->cantidad_personas) {
        return response()->json([
            'message' => 'No hay cupos suficientes'
        ], 400);
    }

    // 4. CREAR RESERVA (usuario desde token, o desde form si es admin)
    $usuarioId = $request->user()->id;
    $estado = 'PENDIENTE';

    if ($request->user()->role->value === 'ADMIN') {
        $usuarioId = $request->usuario_id ?? $usuarioId;
        $estado = $request->estado ?? 'PENDIENTE';
    }

    // Evitar reservas duplicadas para el mismo usuario en la misma actividad
    $reservaExistente = \App\Models\Reserva::where('usuario_id', $usuarioId)
        ->where('actividad_id', $request->actividad_id)
        ->where('estado', '!=', 'CANCELADA')
        ->first();

    if ($reservaExistente) {
        return response()->json([
            'message' => 'Ya tienes una reserva activa para esta actividad.'
        ], 400);
    }

    $reserva = Reserva::create([
        'usuario_id' => $usuarioId,
        'actividad_id' => $request->actividad_id,
        'cantidad_personas' => $request->cantidad_personas,
        'estado' => $estado,
        'notas' => $request->notas
    ]);

    // 5. RESTAR CUPOS
    $actividad->cupo_disponible -= (int)$request->cantidad_personas;
    
    // Si se queda sin cupos, marcar como COMPLETA
    if ($actividad->cupo_disponible === 0) {
        $actividad->estado = \App\Enums\EstadoActividad::COMPLETA;
    }

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

        $actividad = ActividadProgramada::find($reserva->actividad_id);

        $viejosCuposOcupados = ($reserva->estado === 'CANCELADA') ? 0 : $reserva->cantidad_personas;
        $nuevoEstado = $request->estado;
        $nuevosCuposOcupados = ($nuevoEstado === 'CANCELADA') ? 0 : $reserva->cantidad_personas;

        $diferencia = $nuevosCuposOcupados - $viejosCuposOcupados;

        if ($diferencia > 0) {
            if ($actividad->cupo_disponible < $diferencia) {
                return response()->json(['message' => 'No hay cupos suficientes para reactivar esta reserva'], 400);
            }
            $actividad->cupo_disponible -= $diferencia;
        } elseif ($diferencia < 0) {
            $actividad->cupo_disponible += abs($diferencia);
        }

        if ($actividad->cupo_disponible <= 0) {
            $actividad->estado = \App\Enums\EstadoActividad::COMPLETA;
        } else if ($actividad->estado === \App\Enums\EstadoActividad::COMPLETA && $actividad->cupo_disponible > 0) {
            $actividad->estado = \App\Enums\EstadoActividad::PROGRAMADA;
        }
        $actividad->save();

        $reserva->estado = $nuevoEstado;
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

        // DEVOLVER CUPOS A LA ACTIVIDAD
        $actividad = ActividadProgramada::find($reserva->actividad_id);
        if ($actividad) {
            $actividad->cupo_disponible += $reserva->cantidad_personas;
            
            // Si estaba completa, vuelve a estar programada
            if ($actividad->estado === \App\Enums\EstadoActividad::COMPLETA && $actividad->cupo_disponible > 0) {
                $actividad->estado = \App\Enums\EstadoActividad::PROGRAMADA;
            }
            
            $actividad->save();
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
$actividad = ActividadProgramada::find($reserva->actividad_id);

    // 🔥 AJUSTE DE CUPOS (MANEJA TANTO CAMBIO DE CANTIDAD COMO DE ESTADO)
    $viejosCuposOcupados = ($reserva->estado === 'CANCELADA') ? 0 : $reserva->cantidad_personas;
    $nuevaCantidad = $request->has('cantidad_personas') ? (int)$request->cantidad_personas : (int)$reserva->cantidad_personas;
    $nuevoEstado = $request->has('estado') ? $request->estado : $reserva->estado;
    $nuevosCuposOcupados = ($nuevoEstado === 'CANCELADA') ? 0 : $nuevaCantidad;

    $diferencia = $nuevosCuposOcupados - $viejosCuposOcupados;

    if ($diferencia > 0) {
        if ($actividad->cupo_disponible < $diferencia) {
            return response()->json([
                'message' => 'No hay cupos suficientes para realizar este cambio'
            ], 400);
        }
        $actividad->cupo_disponible -= $diferencia;
    } elseif ($diferencia < 0) {
        $actividad->cupo_disponible += abs($diferencia);
    }

    // Ajustar estado automáticamente
    if ($actividad->cupo_disponible <= 0) {
        $actividad->cupo_disponible = 0;
        $actividad->estado = \App\Enums\EstadoActividad::COMPLETA;
    } else if ($actividad->estado === \App\Enums\EstadoActividad::COMPLETA && $actividad->cupo_disponible > 0) {
        $actividad->estado = \App\Enums\EstadoActividad::PROGRAMADA;
    }

    $actividad->save();

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