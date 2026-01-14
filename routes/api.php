<?php

use App\Http\Controllers\AuthController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/users', function(){
    return UserResource::collection(User::all());
});

// get single user
Route::get('/users/{id}',function(string $id){
    return new UserResource(User::findOrFail($id));
});

// create user
Route::post('/users', function(Request $request){
    $user = User::create($request->all());
    return new UserResource($user);
});

// auth api
Route::post('/register',[AuthController::class, 'register']);
Route::post('/login', [AuthController::class,'Login']);

Route::group([
    'middleware' => ['auth:sanctum']
], function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    Route::apiResource('/products', ProductController::class);
});