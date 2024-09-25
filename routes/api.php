<?php

use App\Http\Controllers\DemoController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    
    if (Auth::attempt($credentials)) {
        $users = $request->user();

        $token = $users->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    return response()->json([
        'message' => 'invalid user'
    ]);
}); 

Route::middleware('auth:sanctum')->get('/users/profile', function (Request $request) {
    return $request->user();
});

// API Routesv
Route ::post('/register', [UserController::class,'register']);
Route ::post('/login', [UserController::class,'login']);

Route ::group([
    'middleware' => ['auth:api']
], function(){

    Route::get('profile', [UserController::class, 'profile']);
    Route::get('refresh', [UserController::class, 'prefreshToken']);
    Route::get('profile', [UserController::class, 'logout']);
});
// API Routes^

//DEMO
Route ::get('/demo',[DemoController::class,'index']);
//DEMO

//USER
Route ::get('/users',[UserController::class,'index']);
Route ::get('/users/{user}',[UserController::class,'show']);
Route ::post('/users',[UserController::class,'store']);

Route ::delete('/users/{user}',[UserController::class,'destroy']);
Route ::post('/upload',[UserController::class,'upload']);
//USER

//POKEMON\\
Route::apiResource('pokemon', PokemonController::class);
//POKEMON\\

Route::put('/users/{id}', [UserController::class, 'update']);
