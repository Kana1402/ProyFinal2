<?php

use App\Models\ActividadProgramada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReservaController;
/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (NIVEL VISITANTE)
|--------------------------------------------------------------------------
| Estas rutas NO están protegidas por el middleware 'auth:sanctum'.
| Cualquier persona puede acceder a ellas sin necesidad de enviar un token.
| Son ideales para iniciar sesión, registrarse, o ver información pública
| (como noticias, servicios disponibles, etc).
*/

Route::post('/login', function (Request $request) {
    $user = \App\Models\User::where('correo', $request->email)
        ->orWhere('username', $request->email)
        ->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    $token = $user->createToken('token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'username' => $user->username,
            'correo' => $user->correo,
            'role' => $user->role->value,
        ],
    ]);
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
        'role' => \App\Enums\Role::USER->value, // Por defecto al registrarse son USER
    ]);

    $token = $user->createToken('token')->plainTextToken;

    return response()->json(['token' => $token]);
});

Route::apiResource('noticias', \App\Http\Controllers\Api\NoticiaController::class)
    ->only(['index', 'show']);

Route::apiResource('miembros-directiva', \App\Http\Controllers\Api\MiembroDirectivaController::class)
    ->only(['index', 'show']);

Route::apiResource('servicios', \App\Http\Controllers\Api\ServicioController::class)
    ->only(['index', 'show']);

Route::get('/servicios/{servicio}/actividades', [\App\Http\Controllers\Api\ActividadProgramadaController::class, 'porServicio']);


/*
|--------------------------------------------------------------------------
| RUTAS PRIVADAS (REQUIEREN INICIAR SESIÓN)
|--------------------------------------------------------------------------
| Todo lo que esté dentro de este grupo 'auth:sanctum' requiere que el 
| usuario envíe un token Bearer válido.
*/
Route::middleware('auth:sanctum')->group(function () {

    // --- RUTAS PARA CUALQUIER USUARIO LOGUEADO (USER Y ADMIN) ---
    // Ejemplos: Ver su propio perfil, cerrar sesión, apuntarse a un servicio.
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('/perfil', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    });

    Route::get('/actividades-programadas/usuario', function (Request $request) {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
            'data' => ActividadProgramada::with('servicio')->orderBy('fecha_hora')->get(),
            'message' => 'Actividades programadas accesibles con sesión iniciada',
        ]);

    });
// Reservas
    Route::get('/reservas', [ReservaController::class, 'index']);
    Route::post('/reservas', [ReservaController::class, 'store']);
    Route::get('/reservas/{id}', [ReservaController::class, 'show']);
    Route::delete('/reservas/{id}', [ReservaController::class, 'destroy']);
    // Rutas del controlador de usuarios: 
    // Exceptuamos 'index' y 'destroy' para que los usuarios normales NO 
    // puedan ver la lista de todos los usuarios ni eliminarlos.
    Route::apiResource('users', \App\Http\Controllers\Api\UserController::class)
        ->except(['destroy', 'index']);


    /*
    |--------------------------------------------------------------------------
    | RUTAS EXCLUSIVAS DEL ADMINISTRADOR
    |--------------------------------------------------------------------------
    | Al anidar este middleware 'role:ADMIN' dentro de 'auth:sanctum', le 
    | estamos diciendo a Laravel: "Para entrar aquí, primero debes tener un
    | token válido, Y además, tu rol debe ser exactamente ADMIN".
    */
    Route::middleware('role:ADMIN')->group(function () {
        
        // El administrador sí puede ver la lista de todos los usuarios
        Route::get('/users', [\App\Http\Controllers\Api\UserController::class, 'index']); 
        
        // El administrador sí puede eliminar a cualquier usuario
        Route::delete('/users/{user}', [\App\Http\Controllers\Api\UserController::class, 'destroy']); 

        // El administrador puede crear, editar y eliminar noticias 
        Route::apiResource('noticias', \App\Http\Controllers\Api\NoticiaController::class)
            ->only(['store', 'update', 'destroy']);

        // El administrador puede crear, editar y eliminar miembros de la directiva
        Route::apiResource('miembros-directiva', \App\Http\Controllers\Api\MiembroDirectivaController::class)
            ->only(['store', 'update', 'destroy']);

        Route::apiResource('servicios', \App\Http\Controllers\Api\ServicioController::class)
            ->only(['store', 'update', 'destroy']);

        Route::apiResource('actividades-programadas', \App\Http\Controllers\Api\ActividadProgramadaController::class);
        
        // Reservas 
        Route::get('/reservas', [ReservaController::class, 'index']);
    Route::get('/reservas/{id}', [ReservaController::class, 'show']);
    Route::put('/reservas/{id}', [ReservaController::class, 'update']); 
    Route::delete('/reservas/{id}', [ReservaController::class, 'destroy']);
    });

    
});
