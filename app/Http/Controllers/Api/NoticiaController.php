<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Noticia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoticiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $noticias = Noticia::with('autor')
            ->orderBy('fecha_publicacion', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $noticias,
            'message' => 'Listado de noticias obtenido exitosamente',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:200',
            'contenido' => 'required|string',
            'imagen_url' => 'nullable|string|max:255',
            'autor_id' => 'required|exists:usuarios,id',
        ]);

        $noticia = Noticia::create($validated);

        return response()->json([
            'success' => true,
            'data' => $noticia->load('autor'),
            'message' => 'Noticia creada exitosamente',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $noticia = Noticia::with('autor')->find($id);

        if (! $noticia) {
            return response()->json([
                'success' => false,
                'message' => 'Noticia no encontrada',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $noticia,
            'message' => 'Noticia obtenida exitosamente',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $noticia = Noticia::find($id);

        if (! $noticia) {
            return response()->json([
                'success' => false,
                'message' => 'Noticia no encontrada',
            ], 404);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:200',
            'contenido' => 'required|string',
            'imagen_url' => 'nullable|string|max:255',
            'autor_id' => 'required|exists:usuarios,id',
        ]);

        $noticia->update($validated);

        return response()->json([
            'success' => true,
            'data' => $noticia->load('autor'),
            'message' => 'Noticia actualizada exitosamente',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $noticia = Noticia::find($id);

        if (! $noticia) {
            return response()->json([
                'success' => false,
                'message' => 'Noticia no encontrada',
            ], 404);
        }

        $noticia->delete();

        return response()->json([
            'success' => true,
            'message' => 'Noticia eliminada exitosamente',
        ]);
    }
}
