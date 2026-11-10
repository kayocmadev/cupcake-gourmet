<?php

namespace Database\Factories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Produto> */
class ProdutoFactory extends Factory
{
    protected $model = Produto::class;

    public function definition(): array
    {
        return [
            'nome' => 'Cupcake de '.fake()->word(),
            'descricao' => fake()->sentence(12),
            'sabor' => fake()->randomElement(['Chocolate', 'Baunilha', 'Morango', 'Limão']),
            'preco' => fake()->randomFloat(2, 6, 14),
            'ativo' => true,
        ];
    }

    public function inativo(): static
    {
        return $this->state(fn () => ['ativo' => false]);
    }
}
