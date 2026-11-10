<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    public const RECEBIDO = 'recebido';
    public const EM_PREPARO = 'em_preparo';
    public const SAIU_PARA_ENTREGA = 'saiu_para_entrega';
    public const ENTREGUE = 'entregue';
    public const CANCELADO = 'cancelado';

    /** Ordem do andamento normal do pedido (RN07). */
    public const FLUXO = [
        self::RECEBIDO,
        self::EM_PREPARO,
        self::SAIU_PARA_ENTREGA,
        self::ENTREGUE,
    ];

    public const ROTULOS = [
        self::RECEBIDO => 'Recebido',
        self::EM_PREPARO => 'Em preparo',
        self::SAIU_PARA_ENTREGA => 'Saiu para entrega',
        self::ENTREGUE => 'Entregue',
        self::CANCELADO => 'Cancelado',
    ];

    protected $fillable = [
        'user_id', 'status', 'taxa_entrega', 'total',
        'endereco_entrega', 'forma_pagamento', 'observacao',
    ];

    protected function casts(): array
    {
        return [
            'taxa_entrega' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(ItemPedido::class);
    }

    public function rotuloStatus(): string
    {
        return self::ROTULOS[$this->status] ?? $this->status;
    }

    /** Soma dos itens, sem a taxa de entrega. */
    public function subtotal(): float
    {
        return round((float) $this->total - (float) $this->taxa_entrega, 2);
    }

    /** O cliente só pode cancelar antes de o pedido sair para entrega (RN08). */
    public function podeSerCancelado(): bool
    {
        return in_array($this->status, [self::RECEBIDO, self::EM_PREPARO], true);
    }

    public function cancelar(): void
    {
        if (! $this->podeSerCancelado()) {
            throw new \DomainException('Este pedido não pode mais ser cancelado.');
        }

        $this->update(['status' => self::CANCELADO]);
    }

    /** Próximo status do fluxo normal, ou null se o pedido já terminou. */
    public function proximoStatus(): ?string
    {
        $posicao = array_search($this->status, self::FLUXO, true);

        if ($posicao === false) {
            return null;
        }

        return self::FLUXO[$posicao + 1] ?? null;
    }

    public function avancarStatus(): void
    {
        $proximo = $this->proximoStatus();

        if ($proximo === null) {
            throw new \DomainException('Este pedido não pode mais avançar de status.');
        }

        $this->update(['status' => $proximo]);
    }
}
