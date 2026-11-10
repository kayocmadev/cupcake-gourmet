<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CardapioController extends Controller
{
    /** Home do sistema: destaque, cardápio com filtro por categoria e busca (RF03). */
    public function index(Request $request): View
    {
        $busca = $request->query('busca');
        $categoria = $request->query('categoria');
        $categoria = array_key_exists((string) $categoria, Produto::CATEGORIAS) ? $categoria : null;

        $produtos = Produto::ativos()
            ->daCategoria($categoria)
            ->busca($busca)
            ->orderBy('nome')
            ->get();

        return view('cardapio.index', [
            'produtos' => $produtos,
            'porCategoria' => $produtos->groupBy('categoria'),
            'busca' => $busca,
            'categoria' => $categoria,
            'filtrando' => $categoria !== null || filled($busca),
            'destaques' => Produto::ativos()->where('destaque', true)->orderBy('nome')->limit(3)->get(),
        ]);
    }

    /** Detalhes de um cupcake (RF04). Cupcakes desativados não são exibidos (RN09). */
    public function show(Produto $produto): View
    {
        abort_unless($produto->ativo, 404);

        $parecidos = Produto::ativos()
            ->where('categoria', $produto->categoria)
            ->whereKeyNot($produto->id)
            ->orderBy('nome')
            ->limit(3)
            ->get();

        return view('cardapio.show', ['produto' => $produto, 'parecidos' => $parecidos]);
    }
}
