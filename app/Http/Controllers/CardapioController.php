<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CardapioController extends Controller
{
    /** Home do sistema: cardápio com busca por nome ou sabor (RF03). */
    public function index(Request $request): View
    {
        $busca = $request->query('busca');

        $produtos = Produto::ativos()
            ->busca($busca)
            ->orderBy('nome')
            ->get();

        return view('cardapio.index', ['produtos' => $produtos, 'busca' => $busca]);
    }

    /** Detalhes de um cupcake (RF04). Cupcakes desativados não são exibidos (RN09). */
    public function show(Produto $produto): View
    {
        abort_unless($produto->ativo, 404);

        return view('cardapio.show', ['produto' => $produto]);
    }
}
