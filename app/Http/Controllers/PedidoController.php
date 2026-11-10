<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Services\Carrinho;
use App\Services\PedidoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function __construct(private Carrinho $carrinho, private PedidoService $pedidos)
    {
    }

    /** RF07: lista dos pedidos do cliente, do mais recente ao mais antigo. */
    public function index(Request $request): View
    {
        $pedidos = $request->user()->pedidos()->latest()->get();

        return view('pedidos.index', ['pedidos' => $pedidos]);
    }

    /** Tela de finalização: endereço, pagamento e resumo do carrinho. */
    public function create(Request $request): View|RedirectResponse
    {
        if ($this->carrinho->vazio()) {
            return redirect()->route('cardapio.index')
                ->with('erro', 'Seu carrinho está vazio. Escolha ao menos um cupcake para finalizar o pedido.');
        }

        return view('pedidos.create', [
            'itens' => $this->carrinho->itens(),
            'subtotal' => $this->carrinho->subtotal(),
            'taxa' => $this->carrinho->taxaEntrega(),
            'formasPagamento' => config('loja.formas_pagamento'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($this->carrinho->vazio()) {
            return redirect()->route('cardapio.index')
                ->with('erro', 'Seu carrinho está vazio. Escolha ao menos um cupcake para finalizar o pedido.');
        }

        $dados = $request->validate([
            'endereco_entrega' => ['required', 'string', 'min:10', 'max:255'],
            'forma_pagamento' => ['required', Rule::in(array_keys(config('loja.formas_pagamento')))],
            'observacao' => ['nullable', 'string', 'max:255'],
        ], [
            'endereco_entrega.min' => 'Informe o endereço completo: rua, número e bairro.',
        ]);

        $pedido = $this->pedidos->criarAPartirDoCarrinho($request->user(), $dados);

        return redirect()->route('pedidos.show', $pedido)
            ->with('sucesso', "Pedido #{$pedido->id} realizado com sucesso!");
    }

    public function show(Pedido $pedido): View
    {
        Gate::authorize('view', $pedido);

        return view('pedidos.show', [
            'pedido' => $pedido->load('itens.produto'),
            'formasPagamento' => config('loja.formas_pagamento'),
        ]);
    }

    /** RF08: cancelamento pelo cliente, apenas antes de sair para entrega (RN08). */
    public function cancelar(Pedido $pedido): RedirectResponse
    {
        Gate::authorize('cancel', $pedido);

        if (! $pedido->podeSerCancelado()) {
            return redirect()->route('pedidos.show', $pedido)
                ->with('erro', 'Este pedido já saiu para entrega ou foi finalizado e não pode mais ser cancelado.');
        }

        $pedido->cancelar();

        return redirect()->route('pedidos.show', $pedido)->with('sucesso', "Pedido #{$pedido->id} cancelado.");
    }
}
