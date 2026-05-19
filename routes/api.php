<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {});

Route::post('/login', function (Request $request) {
    $user = \App\Models\User::where('correo', $request->email)
        ->orWhere('username', $request->email)
        ->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    $token = $user->createToken('token')->plainTextToken;

    return response()->json(['token' => $token]);
});

Route::post('/register', function (Request $request) {
    $request->validate([
        'username' => 'required|unique:usuarios,username',
        'email' => 'required|email|unique:usuarios,correo',
        'telefono' => 'nullable|string|max:20',
        'password' => 'required'
    ]);

    $user = \App\Models\User::create([
        'username' => $request->username,
        'correo' => $request->email,
        'telefono' => $request->telefono,
        'password' => $request->password,
        'role' => \App\Enums\Role::USER->value,
    ]);

    $token = $user->createToken('token')->plainTextToken;

    return response()->json(['token' => $token]);
});

Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Sesión cerrada'
    ]);
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/perfil', function (Request $request) {
    return $request->user();
});
