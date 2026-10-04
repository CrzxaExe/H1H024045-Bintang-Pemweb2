<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\MatakuliahController;
use App\Http\Controllers\Api\ProgramStudiController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/profil', [AuthController::class, 'profil']);
    Route::put('/auth/password', [AuthController::class, 'password']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/logout-semua', [
        AuthController::class,
        'logoutSemua'
    ]);
    Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
    Route::get('/mahasiswa/{mahasiswa}', [
        MahasiswaController::class,
        'show'
    ]);
    Route::middleware('ability:mahasiswa:tulis')->group(function () {
        Route::post('/mahasiswa', [MahasiswaController::class, 'store']);
        Route::put('/mahasiswa/{mahasiswa}', [
            MahasiswaController::class,
            'update'
        ]);
        Route::patch('/mahasiswa/{mahasiswa}', [
            MahasiswaController::class,
            'update'
        ]);
        Route::delete('/mahasiswa/{mahasiswa}', [
            MahasiswaController::class,
            'destroy'
        ])->middleware('peran.admin');
    });
});
Route::apiResource('matakuliah', MatakuliahController::class);
Route::get('/program-studi/{programStudi}/mahasiswa', [ProgramStudiController::class, 'mahasiswa']);
