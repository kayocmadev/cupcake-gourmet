<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $fillable = ['nome', 'descricao', 'sabor', 'preco', 'ativo'];

    protected function casts(): array
    {
        return [
            'preco' => 'decimal:2',
            'ativo' => 'boolean',
        ];
    }

    /** Cupcakes que aparecem no cardápio (RN09). */
    public function scopeAtivos(Builder $query): Builder
    {
        return $query->where('ativo', true);
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
