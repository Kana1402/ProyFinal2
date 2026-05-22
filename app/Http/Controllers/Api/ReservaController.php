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
        $actividad = ActividadProgramada::find($request->actividad_id);

        if (!$actividad) {
            return response()->json([
                'message' => 'Actividad no encontrada'
            ], 404);
        }

        // Verificar cupos
        if ($actividad->cupo_disponible < $request->cantidad_personas) {

            return response()->json([
                'message' => 'No hay cupos suficientes'
            ], 400);
        }

        // Crear reserva
        $reserva = Reserva::create([
            'usuario_id' => $request->usuario_id,
            'actividad_id' => $request->actividad_id,
            'cantidad_personas' => $request->cantidad_personas,
            'estado' => 'PENDIENTE',
            'notas' => $request->notas
        ]);

        // Restar cupos
        $actividad->cupo_disponible -= $request->cantidad_personas;
        $actividad->save();

        return response()->json($reserva, 201);
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
}