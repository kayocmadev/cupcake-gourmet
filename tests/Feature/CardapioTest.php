<?php

namespace Tests\Feature;

use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CardapioTest extends TestCase
{
    use RefreshDatabase;

    public function test_cardapio_lista_apenas_cupcakes_ativos(): void
    {
        Produto::factory()->create(['nome' => 'Brigadeiro Teste']);
        Produto::factory()->inativo()->create(['nome' => 'Cupcake Escondido']);

        $resposta = $this->get('/');

        $resposta->assertOk();
        $resposta->assertSee('Brigadeiro Teste');
        $resposta->assertDontSee('Cupcake Escondido');
    }

    public function test_busca_filtra_por_nome_ou_sabor(): void
    {
        Produto::factory()->create(['nome' => 'Cupcake Alfa', 'sabor' => 'Morango']);
        Produto::factory()->create(['nome' => 'Cupcake Beta', 'sabor' => 'Limão']);

        $this->get('/?busca=Morango')
            ->assertOk()
            ->assertSee('Cupcake Alfa')
            ->assertDontSee('Cupcake Beta');
    }

    public function test_busca_sem_resultado_mostra_mensagem(): void
    {
        Produto::factory()->create(['nome' => 'Cupcake Alfa']);

        $this->get('/?busca=inexistente')
            ->assertOk()
            ->assertSee('Nenhum cupcake encontrado');
    }

    public function test_detalhe_do_cupcake_mostra_descricao_e_preco(): void
    {
        $produto = Produto::factory()->create(['descricao' => 'Descrição de teste', 'preco' => 9.5]);

        $this->get(route('cardapio.show', $produto))
            ->assertOk()
            ->assertSee('Descrição de teste')
            ->assertSee('R$ 9,50');
    }

    public function test_cupcake_desativado_retorna_404_no_detalhe(): void
    {
        $produto = Produto::factory()->inativo()->create();

        $this->get(route('cardapio.show', $produto))->assertNotFound();
    }
}
