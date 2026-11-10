<?php

namespace Database\Seeders;

use App\Models\Produto;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        // [nome, sabor, categoria, preço, imagem, destaque, descrição]
        $cupcakes = [
            ['Baunilha Clássica', 'Baunilha', 'tradicionais', 9.00, 'baunilha-classica', true,
                'Massa fofinha de baunilha com redemoinhos de buttercream cremoso. O sabor que todo mundo conhece e ama.'],
            ['Chocolate Intenso', 'Chocolate', 'tradicionais', 10.00, 'chocolate-intenso', true,
                'Massa úmida de cacau coberta com um generoso redemoinho de ganache de chocolate meio amargo.'],
            ['Cenoura com Cream Cheese', 'Cenoura', 'tradicionais', 9.50, 'cenoura-cream-cheese', false,
                'Massa de cenoura macia, cobertura de cream cheese e uma cenourinha de açúcar feita à mão.'],
            ['Morango com Chantilly', 'Morango', 'frutas', 11.00, 'morango-chantilly', true,
                'Massa de baunilha, creme de baunilha e chantilly, finalizado com morango fresco por cima.'],
            ['Frutas Vermelhas', 'Frutas vermelhas', 'frutas', 12.00, 'frutas-vermelhas', false,
                'Cobertura rosada de frutas vermelhas com morangos e framboesas frescas sobre massa de baunilha.'],
            ['Limão Siciliano', 'Limão', 'frutas', 10.50, 'limao-siciliano', false,
                'Massa cítrica de limão com chantilly leve, raspas e uma fatia de limão siciliano no topo.'],
            ['Frutas do Bosque', 'Mirtilo', 'frutas', 12.50, 'frutas-do-bosque', false,
                'Creme de frutas silvestres coberto com mirtilos, morango e frutas amarelas, em massa amanteigada.'],
            ['Red Velvet', 'Red velvet', 'gourmet', 11.50, 'red-velvet', true,
                'Massa aveludada vermelha com chantilly cremoso, farelo de red velvet e um morango no topo.'],
            ['Caramelo e Pipoca', 'Caramelo', 'gourmet', 12.00, 'caramelo-pipoca', false,
                'Buttercream de baunilha, calda de caramelo e pipoca crocante sobre massa de baunilha.'],
            ['Pistache Crocante', 'Pistache', 'gourmet', 13.50, 'pistache-crocante', false,
                'Creme de baunilha com farelo de pistache e massa amanteigada. Para quem gosta de crocância.'],
        ];

        // Remove cupcakes de versões anteriores do cardápio de demonstração que não estão mais na lista.
        Produto::whereNotIn('nome', array_column($cupcakes, 0))->where('descricao', 'like', '%Massa%')->doesntHave('itens')->delete();

        foreach ($cupcakes as [$nome, $sabor, $categoria, $preco, $imagem, $destaque, $descricao]) {
            Produto::updateOrCreate(
                ['nome' => $nome],
                [
                    'sabor' => $sabor,
                    'categoria' => $categoria,
                    'descricao' => $descricao,
                    'preco' => $preco,
                    'imagem' => "img/cupcakes/{$imagem}.jpg",
                    'destaque' => $destaque,
                    'ativo' => true,
                ],
            );
        }
    }
}
