<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PedidoService
{
    public function __construct(private Carrinho $carrinho)
    {
    }

    /**
     * Transforma o carrinho em pedido (RF06). Tudo é gravado em uma transação:
     * ou o pedido e seus itens entram juntos, ou nada é gravado.
     *
     * @param  array{endereco_entrega: string, forma_pagamento: string, observacao?: ?string}  $dados
     *
     * @throws \DomainException quando o carrinho está vazio (RN03)
     */
    public function criarAPartirDoCarrinho(User $cliente, array $dados): Pedido
    {
        $itens = $this->carrinho->itens();

        if ($itens->isEmpty()) {
            throw new \DomainException('O carrinho está vazio.');
        }

        $taxa = $this->carrinho->taxaEntrega();

        $pedido = DB::transaction(function () use ($cliente, $dados, $itens, $taxa) {
            $pedido = Pedido::create([
                'user_id' => $cliente->id,
                'status' => Pedido::RECEBIDO,
                'taxa_entrega' => $taxa,
                'total' => round($itens->sum('subtotal') + $taxa, 2),
                'endereco_entrega' => $dados['endereco_entrega'],
                'forma_pagamento' => $dados['forma_pagamento'],
                'observacao' => $dados['observacao'] ?? null,
            ]);

            foreach ($itens as $item) {
                // O preço é copiado para o item para não mudar pedidos antigos (RN05).
                $pedido->itens()->create([
                    'produto_id' => $item['produto']->id,
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['produto']->preco,
                ]);
            }

            return $pedido;
        });

        $this->carrinho->esvaziar();

        return $pedido;
    }
}
