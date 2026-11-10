<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Services\Carrinho;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarrinhoController extends Controller
{
    public function __construct(private Carrinho $carrinho)
    {
    }

    public function index(): View
    {
        return view('carrinho.index', [
            'itens' => $this->carrinho->itens(),
            'subtotal' => $this->carrinho->subtotal(),
            'taxa' => (float) config('loja.taxa_entrega'),
        ]);
    }

    /** RF05: adicionar ao carrinho. Cupcake desativado não pode ser adicionado (RN09). */
    public function adicionar(Request $request, Produto $produto): RedirectResponse
    {
        abort_unless($produto->ativo, 404);

        $dados = $request->validate([
            'quantidade' => $this->regraQuantidade(),
        ]);

        $quantidade = $this->carrinho->adicionar($produto, (int) $dados['quantidade']);

        $mensagem = $quantidade === (int) config('loja.quantidade_maxima')
            ? "{$produto->nome}: quantidade máxima de {$quantidade} unidades por pedido."
            : "{$produto->nome} adicionado ao carrinho.";

        return back()->with('sucesso', $mensagem);
    }

    public function atualizar(Request $request, Produto $produto): RedirectResponse
    {
        $dados = $request->validate([
            'quantidade' => $this->regraQuantidade(),
        ]);

        $this->carrinho->atualizar($produto, (int) $dados['quantidade']);

        return redirect()->route('carrinho.index')->with('sucesso', 'Carrinho atualizado.');
    }

    public function remover(Produto $produto): RedirectResponse
    {
        $this->carrinho->remover($produto);

        return redirect()->route('carrinho.index')->with('sucesso', "{$produto->nome} removido do carrinho.");
    }

    private function regraQuantidade(): array
    {
        return [
            'required',
            'integer',
            'between:'.config('loja.quantidade_minima').','.config('loja.quantidade_maxima'),
        ];
    }
}
