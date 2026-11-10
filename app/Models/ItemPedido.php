<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPedido extends Model
{
    protected $table = 'itens_pedido';

    protected $fillable = ['pedido_id', 'produto_id', 'quantidade', 'preco_unitario'];

    protected function casts(): array
    {
        return [
            'preco_unitario' => 'decimal:2',
            'quantidade' => 'integer',
        ];
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function subtotal(): float
    {
        return round($this->quantidade * (float) $this->preco_unitario, 2);
    }
}
