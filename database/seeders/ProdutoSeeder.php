<?php

namespace Database\Seeders;

use App\Models\Produto;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        $cupcakes = [
            ['Brigadeiro Cremoso', 'Chocolate', 'Massa de chocolate com recheio e cobertura de brigadeiro cremoso e granulado.', 9.50],
            ['Ninho com Morango', 'Leite Ninho', 'Massa de baunilha, creme de leite Ninho e morangos frescos por cima.', 10.00],
            ['Limão Siciliano', 'Limão', 'Massa leve de limão com cobertura de chantilly cítrico e raspas de limão.', 8.50],
            ['Red Velvet', 'Red Velvet', 'Massa aveludada vermelha com cobertura de cream cheese.', 11.00],
            ['Doce de Leite', 'Doce de leite', 'Massa de baunilha recheada com doce de leite e flocos de coco.', 9.00],
            ['Cenoura com Chocolate', 'Cenoura', 'Massa fofinha de cenoura com cobertura de chocolate meio amargo.', 8.00],
            ['Pistache', 'Pistache', 'Massa de pistache com creme de manteiga e pedacinhos da castanha.', 12.50],
            ['Maracujá', 'Maracujá', 'Massa de baunilha com recheio de mousse de maracujá.', 9.00],
        ];

        foreach ($cupcakes as [$nome, $sabor, $descricao, $preco]) {
            Produto::updateOrCreate(
                ['nome' => $nome],
                ['sabor' => $sabor, 'descricao' => $descricao, 'preco' => $preco, 'ativo' => true],
            );
        }
    }
}
