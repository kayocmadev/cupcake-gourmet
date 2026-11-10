<?php

namespace App\Services;

use App\Models\Produto;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;

/**
 * Carrinho de compras guardado na sessão do usuário. Só vira Pedido na finalização.
 */
class Carrinho
{
    private const CHAVE = 'carrinho';

    public function __construct(private Session $sessao)
    {
    }

    /** Soma a quantidade ao item, respeitando o limite por cupcake (RN04). Retorna a quantidade final. */
    public function adicionar(Produto $produto, int $quantidade): int
    {
        $atual = $this->quantidadeDe($produto->id);

        return $this->definir($produto->id, $atual + $quantidade);
    }

    /** Define a quantidade do item. Quantidade menor que o mínimo remove o item. */
    public function atualizar(Produto $produto, int $quantidade): int
    {
        return $this->definir($produto->id, $quantidade);
    }

    public function remover(Produto $produto): void
    {
        $itens = $this->bruto();
        unset($itens[$produto->id]);
        $this->sessao->put(self::CHAVE, $itens);
    }

    public function esvaziar(): void
    {
        $this->sessao->forget(self::CHAVE);
    }

    /**
     * Itens do carrinho com produto, quantidade e subtotal.
     * Cupcakes desativados ou removidos do cardápio saem do carrinho (RN09).
     *
     * @return Collection<int, array{produto: Produto, quantidade: int, subtotal: float}>
     */
    public function itens(): Collection
    {
        $bruto = $this->bruto();

        if ($bruto === []) {
            return collect();
        }

        $produtos = Produto::ativos()->whereIn('id', array_keys($bruto))->get()->keyBy('id');

        return collect($bruto)
            ->filter(fn ($quantidade, $id) => $produtos->has($id))
            ->map(fn ($quantidade, $id) => [
                'produto' => $produtos[$id],
                'quantidade' => $quantidade,
                'subtotal' => round($quantidade * (float) $produtos[$id]->preco, 2),
            ])
            ->values();
    }

    public function subtotal(): float
    {
        return round($this->itens()->sum('subtotal'), 2);
    }

    public function quantidadeTotal(): int
    {
        return (int) $this->itens()->sum('quantidade');
    }

    public function vazio(): bool
    {
        return $this->itens()->isEmpty();
    }

    private function quantidadeDe(int $produtoId): int
    {
        return $this->bruto()[$produtoId] ?? 0;
    }

    private function definir(int $produtoId, int $quantidade): int
    {
        $itens = $this->bruto();

        if ($quantidade < config('loja.quantidade_minima')) {
            unset($itens[$produtoId]);
            $this->sessao->put(self::CHAVE, $itens);

            return 0;
        }

        $quantidade = min($quantidade, config('loja.quantidade_maxima'));
        $itens[$produtoId] = $quantidade;
        $this->sessao->put(self::CHAVE, $itens);

        return $quantidade;
    }

    /** @return array<int, int> */
    private function bruto(): array
    {
        return $this->sessao->get(self::CHAVE, []);
    }
}
