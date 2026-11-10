<?php

namespace Tests\Feature;

use App\Models\Pedido;
use App\Models\Produto;
use App\Models\User;
use App\Services\Carrinho;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriasEFreteTest extends TestCase
{
    use RefreshDatabase;

    public function test_filtro_por_categoria_mostra_so_os_cupcakes_dela(): void
    {
        Produto::factory()->create(['nome' => 'Cupcake Frutal', 'categoria' => 'frutas']);
        Produto::factory()->create(['nome' => 'Cupcake Tradicional', 'categoria' => 'tradicionais']);

        $this->get('/?categoria=frutas')
            ->assertOk()
            ->assertSee('Cupcake Frutal')
            ->assertDontSee('Cupcake Tradicional');
    }

    public function test_categoria_invalida_e_ignorada(): void
    {
        Produto::factory()->create(['nome' => 'Cupcake Frutal', 'categoria' => 'frutas']);

        $this->get('/?categoria=inexistente')->assertOk()->assertSee('Cupcake Frutal');
    }

    public function test_cardapio_sem_filtro_agrupa_por_categoria(): void
    {
        Produto::factory()->create(['categoria' => 'gourmet']);

        $this->get('/')->assertOk()->assertSee('Gourmet');
    }

    public function test_detalhe_sugere_cupcakes_da_mesma_categoria(): void
    {
        $principal = Produto::factory()->create(['categoria' => 'frutas']);
        Produto::factory()->create(['nome' => 'Parecido Frutal', 'categoria' => 'frutas']);
        Produto::factory()->create(['nome' => 'Outro Gourmet', 'categoria' => 'gourmet']);

        $this->get(route('cardapio.show', $principal))
            ->assertOk()
            ->assertSee('Você também pode gostar')
            ->assertSee('Parecido Frutal')
            ->assertDontSee('Outro Gourmet');
    }

    public function test_taxa_de_entrega_e_cobrada_abaixo_do_valor_do_frete_gratis(): void
    {
        $produto = Produto::factory()->create(['preco' => 10]);
        $this->post(route('carrinho.adicionar', $produto), ['quantidade' => 3]);

        $carrinho = app(Carrinho::class);
        $this->assertSame(8.0, $carrinho->taxaEntrega());
        $this->assertSame(38.0, $carrinho->total());
        $this->assertSame(40.0, $carrinho->faltaParaFreteGratis());
    }

    public function test_frete_gratis_a_partir_do_valor_minimo(): void
    {
        $produto = Produto::factory()->create(['preco' => 10]);
        $this->post(route('carrinho.adicionar', $produto), ['quantidade' => 7]);

        $carrinho = app(Carrinho::class);
        $this->assertSame(0.0, $carrinho->taxaEntrega());
        $this->assertSame(70.0, $carrinho->total());
        $this->assertSame(0.0, $carrinho->faltaParaFreteGratis());

        $this->get('/carrinho')->assertSee('Você ganhou frete grátis');
    }

    public function test_pedido_com_frete_gratis_grava_taxa_zero_e_total_correto(): void
    {
        $cliente = User::factory()->create();
        $produto = Produto::factory()->create(['preco' => 10]);
        $this->actingAs($cliente)->post(route('carrinho.adicionar', $produto), ['quantidade' => 8]);

        $this->actingAs($cliente)->post('/pedidos', [
            'endereco_entrega' => 'Rua das Flores, 100, Centro',
            'forma_pagamento' => 'pix',
        ]);

        $pedido = Pedido::first();
        $this->assertSame('0.00', $pedido->taxa_entrega);
        $this->assertSame('80.00', $pedido->total);
        $this->assertSame(80.0, $pedido->subtotal());
    }

    public function test_pagina_de_creditos_lista_os_fotografos(): void
    {
        $this->get('/creditos')->assertOk()->assertSee('Pexels')->assertSee('Tina Nord');
    }
}
