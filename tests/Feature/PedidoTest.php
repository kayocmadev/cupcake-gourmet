<?php

namespace Tests\Feature;

use App\Models\Pedido;
use App\Models\Produto;
use App\Models\User;
use App\Services\Carrinho;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PedidoTest extends TestCase
{
    use RefreshDatabase;

    private function dadosPedido(array $sobrescrever = []): array
    {
        return array_merge([
            'endereco_entrega' => 'Rua das Flores, 100, Centro',
            'forma_pagamento' => 'pix',
            'observacao' => 'Tocar o interfone',
        ], $sobrescrever);
    }

    private function clienteComCarrinho(): array
    {
        $cliente = User::factory()->create();
        $produto = Produto::factory()->create(['nome' => 'Cupcake Alfa', 'preco' => 10]);

        $this->actingAs($cliente)->post(route('carrinho.adicionar', $produto), ['quantidade' => 3]);

        return [$cliente, $produto];
    }

    private function pedidoDe(User $cliente, string $status = Pedido::RECEBIDO): Pedido
    {
        return Pedido::create([
            'user_id' => $cliente->id,
            'status' => $status,
            'taxa_entrega' => 8,
            'total' => 38,
            'endereco_entrega' => 'Rua das Flores, 100, Centro',
            'forma_pagamento' => 'pix',
        ]);
    }

    public function test_visitante_e_enviado_para_o_login_ao_finalizar(): void
    {
        $this->get('/pedidos/novo')->assertRedirect(route('entrar'));
        $this->post('/pedidos', $this->dadosPedido())->assertRedirect(route('entrar'));
        $this->get('/pedidos')->assertRedirect(route('entrar'));
    }

    public function test_finalizar_com_carrinho_vazio_volta_ao_cardapio_com_aviso(): void
    {
        $cliente = User::factory()->create();

        $this->actingAs($cliente)->get('/pedidos/novo')
            ->assertRedirect(route('cardapio.index'))
            ->assertSessionHas('erro');

        $this->actingAs($cliente)->post('/pedidos', $this->dadosPedido())
            ->assertRedirect(route('cardapio.index'));

        $this->assertDatabaseCount('pedidos', 0);
    }

    public function test_cliente_finaliza_pedido_com_itens_e_total_corretos(): void
    {
        [$cliente, $produto] = $this->clienteComCarrinho();

        $resposta = $this->actingAs($cliente)->post('/pedidos', $this->dadosPedido());

        $pedido = Pedido::first();
        $resposta->assertRedirect(route('pedidos.show', $pedido));

        $this->assertSame($cliente->id, $pedido->user_id);
        $this->assertSame(Pedido::RECEBIDO, $pedido->status);
        $this->assertSame('38.00', $pedido->total);
        $this->assertDatabaseHas('itens_pedido', [
            'pedido_id' => $pedido->id,
            'produto_id' => $produto->id,
            'quantidade' => 3,
            'preco_unitario' => 10,
        ]);
    }

    public function test_carrinho_e_esvaziado_depois_da_finalizacao(): void
    {
        [$cliente] = $this->clienteComCarrinho();

        $this->actingAs($cliente)->post('/pedidos', $this->dadosPedido());

        $this->assertTrue(app(Carrinho::class)->vazio());
    }

    public function test_preco_do_item_nao_muda_quando_o_cardapio_muda(): void
    {
        [$cliente, $produto] = $this->clienteComCarrinho();
        $this->actingAs($cliente)->post('/pedidos', $this->dadosPedido());

        $produto->update(['preco' => 99]);

        $item = Pedido::first()->itens()->first();
        $this->assertSame('10.00', $item->preco_unitario);
        $this->assertSame('38.00', Pedido::first()->total);
    }

    public function test_dados_invalidos_nao_criam_pedido(): void
    {
        [$cliente] = $this->clienteComCarrinho();

        $this->actingAs($cliente)->post('/pedidos', [])
            ->assertSessionHasErrors(['endereco_entrega', 'forma_pagamento']);

        $this->actingAs($cliente)->post('/pedidos', $this->dadosPedido(['endereco_entrega' => 'Rua A']))
            ->assertSessionHasErrors('endereco_entrega');

        $this->actingAs($cliente)->post('/pedidos', $this->dadosPedido(['forma_pagamento' => 'bitcoin']))
            ->assertSessionHasErrors('forma_pagamento');

        $this->assertDatabaseCount('pedidos', 0);
    }

    public function test_cliente_ve_apenas_os_proprios_pedidos_na_lista(): void
    {
        $cliente = User::factory()->create();
        $outro = User::factory()->create();
        $meu = $this->pedidoDe($cliente);
        $alheio = $this->pedidoDe($outro);

        $this->actingAs($cliente)->get('/pedidos')
            ->assertOk()
            ->assertSee('#'.$meu->id)
            ->assertDontSee('#'.$alheio->id.'<', false);
    }

    public function test_cliente_nao_acessa_pedido_de_outro_cliente(): void
    {
        $cliente = User::factory()->create();
        $alheio = $this->pedidoDe(User::factory()->create());

        $this->actingAs($cliente)->get(route('pedidos.show', $alheio))->assertForbidden();
        $this->actingAs($cliente)->post(route('pedidos.cancelar', $alheio))->assertForbidden();

        $this->assertSame(Pedido::RECEBIDO, $alheio->fresh()->status);
    }

    public function test_detalhe_do_pedido_mostra_status_e_itens(): void
    {
        [$cliente] = $this->clienteComCarrinho();
        $this->actingAs($cliente)->post('/pedidos', $this->dadosPedido());

        $this->actingAs($cliente)->get(route('pedidos.show', Pedido::first()))
            ->assertOk()
            ->assertSee('Recebido')
            ->assertSee('Cupcake Alfa')
            ->assertSee('R$ 38,00');
    }

    public function test_cliente_cancela_pedido_recebido(): void
    {
        $cliente = User::factory()->create();
        $pedido = $this->pedidoDe($cliente);

        $this->actingAs($cliente)->post(route('pedidos.cancelar', $pedido))
            ->assertRedirect(route('pedidos.show', $pedido));

        $this->assertSame(Pedido::CANCELADO, $pedido->fresh()->status);
    }

    public function test_cliente_nao_cancela_pedido_que_ja_saiu_para_entrega(): void
    {
        $cliente = User::factory()->create();
        $pedido = $this->pedidoDe($cliente, Pedido::SAIU_PARA_ENTREGA);

        $this->actingAs($cliente)->post(route('pedidos.cancelar', $pedido))
            ->assertSessionHas('erro');

        $this->assertSame(Pedido::SAIU_PARA_ENTREGA, $pedido->fresh()->status);
    }
}
