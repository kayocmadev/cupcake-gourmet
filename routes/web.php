<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardapioController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\PedidoController;
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

Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
Route::post('/carrinho/{produto}', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
Route::patch('/carrinho/{produto}', [CarrinhoController::class, 'atualizar'])->name('carrinho.atualizar');
Route::delete('/carrinho/{produto}', [CarrinhoController::class, 'remover'])->name('carrinho.remover');

Route::middleware('auth')->group(function () {
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/novo', [PedidoController::class, 'create'])->name('pedidos.create');
    Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::post('/pedidos/{pedido}/cancelar', [PedidoController::class, 'cancelar'])->name('pedidos.cancelar');
});
