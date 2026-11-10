<?php

namespace Tests\Feature;

use App\Models\Produto;
use App\Services\Carrinho;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarrinhoTest extends TestCase
{
    use RefreshDatabase;

    public function test_carrinho_comeca_vazio(): void
    {
        $this->get('/carrinho')->assertOk()->assertSee('Seu carrinho está vazio');
    }

    public function test_adicionar_cupcake_ao_carrinho(): void
    {
        $produto = Produto::factory()->create(['nome' => 'Cupcake Alfa', 'preco' => 10]);

        $this->post(route('carrinho.adicionar', $produto), ['quantidade' => 3])
            ->assertSessionHas('sucesso');

        $this->get('/carrinho')
            ->assertSee('Cupcake Alfa')
            ->assertSee('R$ 30,00');
    }

    public function test_adicionar_o_mesmo_cupcake_soma_as_quantidades(): void
    {
        $produto = Produto::factory()->create();

        $this->post(route('carrinho.adicionar', $produto), ['quantidade' => 2]);
        $this->post(route('carrinho.adicionar', $produto), ['quantidade' => 3]);

        $this->assertSame(5, app(Carrinho::class)->quantidadeTotal());
    }

    public function test_quantidade_acima_do_limite_e_rejeitada(): void
    {
        $produto = Produto::factory()->create();

        $this->post(route('carrinho.adicionar', $produto), ['quantidade' => 21])
            ->assertSessionHasErrors('quantidade');

        $this->post(route('carrinho.adicionar', $produto), ['quantidade' => 0])
            ->assertSessionHasErrors('quantidade');

        $this->post(route('carrinho.adicionar', $produto), ['quantidade' => 'abc'])
            ->assertSessionHasErrors('quantidade');

        $this->assertTrue(app(Carrinho::class)->vazio());
    }

    public function test_soma_acima_do_limite_fica_no_maximo(): void
    {
        $produto = Produto::factory()->create();

        $this->post(route('carrinho.adicionar', $produto), ['quantidade' => 15]);
        $this->post(route('carrinho.adicionar', $produto), ['quantidade' => 15]);

        $this->assertSame(20, app(Carrinho::class)->quantidadeTotal());
    }

    public function test_cupcake_desativado_nao_pode_ser_adicionado(): void
    {
        $produto = Produto::factory()->inativo()->create();

        $this->post(route('carrinho.adicionar', $produto), ['quantidade' => 1])->assertNotFound();

        $this->assertTrue(app(Carrinho::class)->vazio());
    }

    public function test_atualizar_quantidade_do_item(): void
    {
        $produto = Produto::factory()->create(['preco' => 5]);
        $this->post(route('carrinho.adicionar', $produto), ['quantidade' => 2]);

        $this->patch(route('carrinho.atualizar', $produto), ['quantidade' => 4])
            ->assertRedirect(route('carrinho.index'));

        $this->assertSame(4, app(Carrinho::class)->quantidadeTotal());
        $this->assertSame(20.0, app(Carrinho::class)->subtotal());
    }

    public function test_remover_item_do_carrinho(): void
    {
        $produto = Produto::factory()->create();
        $this->post(route('carrinho.adicionar', $produto), ['quantidade' => 2]);

        $this->delete(route('carrinho.remover', $produto))->assertRedirect(route('carrinho.index'));

        $this->assertTrue(app(Carrinho::class)->vazio());
    }

    public function test_produto_desativado_depois_de_adicionado_sai_do_carrinho(): void
    {
        $produto = Produto::factory()->create();
        $this->post(route('carrinho.adicionar', $produto), ['quantidade' => 2]);

        $produto->update(['ativo' => false]);

        $this->assertTrue(app(Carrinho::class)->vazio());
    }

    public function test_menu_mostra_contador_de_itens(): void
    {
        $produto = Produto::factory()->create();
        $this->post(route('carrinho.adicionar', $produto), ['quantidade' => 3]);

        $this->get('/')->assertSee('3 itens');
    }
}
