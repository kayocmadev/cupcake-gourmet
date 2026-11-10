<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardapioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CardapioController::class, 'index'])->name('cardapio.index');
Route::get('/cupcakes/{produto}', [CardapioController::class, 'show'])->name('cardapio.show');

Route::middleware('guest')->group(function () {
    Route::get('/cadastro', [AuthController::class, 'formCadastro'])->name('cadastro');
    Route::post('/cadastro', [AuthController::class, 'cadastrar'])->name('cadastro.store');
    Route::get('/entrar', [AuthController::class, 'formLogin'])->name('entrar');
    Route::post('/entrar', [AuthController::class, 'entrar'])->middleware('throttle:10,1')->name('entrar.store');
});

Route::post('/sair', [AuthController::class, 'sair'])->middleware('auth')->name('sair');
