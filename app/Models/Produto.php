<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    public const CATEGORIAS = [
        'tradicionais' => 'Tradicionais',
        'frutas' => 'Frutas',
        'gourmet' => 'Gourmet',
    ];

    protected $fillable = ['nome', 'descricao', 'sabor', 'categoria', 'preco', 'imagem', 'ativo', 'destaque'];

    protected function casts(): array
    {
        return [
            'preco' => 'decimal:2',
            'ativo' => 'boolean',
            'destaque' => 'boolean',
        ];
    }

    public function itens(): HasMany
    {
        return $this->hasMany(ItemPedido::class);
    }

    /** Cupcakes que aparecem no cardápio (RN09). */
    public function scopeAtivos(Builder $query): Builder
    {
        return $query->where('ativo', true);
    }

    public function scopeDaCategoria(Builder $query, ?string $categoria): Builder
    {
        if ($categoria === null || ! array_key_exists($categoria, self::CATEGORIAS)) {
            return $query;
        }

        return $query->where('categoria', $categoria);
    }

    public function rotuloCategoria(): string
    {
        return self::CATEGORIAS[$this->categoria] ?? ucfirst($this->categoria);
    }

    /** Endereço público da foto, ou null quando o cupcake ainda não tem imagem. */
    public function urlImagem(): ?string
    {
        if (! $this->imagem) {
            return null;
        }

        return asset($this->imagem);
    }

    public function scopeBusca(Builder $query, ?string $termo): Builder
    {
        if ($termo === null || trim($termo) === '') {
            return $query;
        }

        $termo = trim($termo);

        return $query->where(function (Builder $q) use ($termo) {
            $q->where('nome', 'like', "%{$termo}%")
                ->orWhere('sabor', 'like', "%{$termo}%");
        });
    }
}
