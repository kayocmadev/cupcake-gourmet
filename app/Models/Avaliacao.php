<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Avaliação enviada por quem testou o sistema (formulário de teste do Projeto Integrador). */
class Avaliacao extends Model
{
    protected $table = 'avaliacoes';

    protected $fillable = [
        'nome', 'data_teste', 'aparelho', 'funcionou', 'nao_funcionou',
        'nao_testado', 'sugestoes', 'nota', 'captura',
    ];

    protected function casts(): array
    {
        return [
            'data_teste' => 'date',
            'nota' => 'integer',
        ];
    }

    public function urlCaptura(): ?string
    {
        return $this->captura ? asset('storage/'.$this->captura) : null;
    }
}
