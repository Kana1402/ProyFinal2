<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Enums\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->user()->role !== Role::ADMIN) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        // Validar los datos de entrada
        $request->validate([
            'username' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo',
            'telefono' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            // El rol es opcional y solo puede ser asignado por un ADMIN
            'role' => 'nullable|string|in:ADMIN,USER,VISITOR',
        ]);

        // Crear el nuevo usuario
        $user = User::create([
            'username' => $request->username,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? Role::USER->value,
        ]);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        if ($request->user()->id !== $user->id && $request->user()->role !== Role::ADMIN) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if ($request->user()->id !== $user->id && $request->user()->role !== Role::ADMIN) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $rules = [
            'username' => 'sometimes|string|max:255',
            'correo' => 'sometimes|email|unique:usuarios,correo,' . $user->id,
            'telefono' => 'nullable|string|max:20',
            'password' => 'sometimes|string|min:6',
        ];

        if ($request->user()->role === Role::ADMIN) {
            $rules['role'] = 'sometimes|string|in:ADMIN,USER,VISITOR';
        }

        $request->validate($rules);

        $data = $request->only(['username', 'correo', 'telefono']);

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->user()->role === Role::ADMIN && $request->filled('role')) {
            $data['role'] = $request->role;
        }

        $user->update($data);

        return response()->json([
            'message' => 'Usuario actualizado con éxito',
            'user' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado con éxito']);
    }
}
