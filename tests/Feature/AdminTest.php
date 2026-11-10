<?php

namespace Tests\Feature;

use App\Models\Pedido;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function pedido(string $status = Pedido::RECEBIDO): Pedido
    {
        return Pedido::create([
            'user_id' => User::factory()->create()->id,
            'status' => $status,
            'taxa_entrega' => 8,
            'total' => 38,
            'endereco_entrega' => 'Rua das Flores, 100, Centro',
            'forma_pagamento' => 'pix',
        ]);
    }

    private function dadosProduto(array $sobrescrever = []): array
    {
        return array_merge([
            'nome' => 'Cupcake Novo',
            'sabor' => 'Coco',
            'categoria' => 'gourmet',
            'descricao' => 'Massa de coco com cobertura cremosa.',
            'preco' => '9.50',
            'ativo' => '1',
        ], $sobrescrever);
    }

    public function test_cliente_comum_nao_acessa_o_painel(): void
    {
        $cliente = User::factory()->create();

        $this->actingAs($cliente)->get('/admin/pedidos')->assertForbidden();
        $this->actingAs($cliente)->get('/admin/cupcakes')->assertForbidden();
        $this->actingAs($cliente)->post(route('admin.pedidos.avancar', $this->pedido()))->assertForbidden();
    }

    public function test_visitante_e_enviado_para_o_login(): void
    {
        $this->get('/admin/pedidos')->assertRedirect(route('entrar'));
    }

    public function test_admin_lista_todos_os_pedidos(): void
    {
        $admin = User::factory()->admin()->create();
        $a = $this->pedido();
        $b = $this->pedido(Pedido::ENTREGUE);

        $this->actingAs($admin)->get('/admin/pedidos')
            ->assertOk()
            ->assertSee('#'.$a->id)
            ->assertSee('#'.$b->id);
    }

    public function test_admin_filtra_pedidos_por_status(): void
    {
        $admin = User::factory()->admin()->create();
        $recebido = $this->pedido(Pedido::RECEBIDO);
        $entregue = $this->pedido(Pedido::ENTREGUE);

        $this->actingAs($admin)->get('/admin/pedidos?status=entregue')
            ->assertOk()
            ->assertSee('#'.$entregue->id)
            ->assertDontSee('#'.$recebido->id.'</a>', false);
    }

    public function test_admin_avanca_o_status_ate_a_entrega(): void
    {
        $admin = User::factory()->admin()->create();
        $pedido = $this->pedido();

        foreach ([Pedido::EM_PREPARO, Pedido::SAIU_PARA_ENTREGA, Pedido::ENTREGUE] as $esperado) {
            $this->actingAs($admin)->post(route('admin.pedidos.avancar', $pedido))->assertSessionHas('sucesso');
            $this->assertSame($esperado, $pedido->fresh()->status);
        }
    }

    public function test_admin_nao_avanca_pedido_entregue_ou_cancelado(): void
    {
        $admin = User::factory()->admin()->create();
        $entregue = $this->pedido(Pedido::ENTREGUE);
        $cancelado = $this->pedido(Pedido::CANCELADO);

        $this->actingAs($admin)->post(route('admin.pedidos.avancar', $entregue))->assertSessionHas('erro');
        $this->actingAs($admin)->post(route('admin.pedidos.avancar', $cancelado))->assertSessionHas('erro');

        $this->assertSame(Pedido::ENTREGUE, $entregue->fresh()->status);
        $this->assertSame(Pedido::CANCELADO, $cancelado->fresh()->status);
    }

    public function test_admin_cadastra_cupcake(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/admin/cupcakes', $this->dadosProduto())
            ->assertRedirect(route('admin.produtos.index'));

        $this->assertDatabaseHas('produtos', ['nome' => 'Cupcake Novo', 'preco' => 9.5, 'ativo' => true]);
    }

    public function test_cadastro_de_cupcake_valida_os_dados(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/admin/cupcakes', [])
            ->assertSessionHasErrors(['nome', 'sabor', 'categoria', 'descricao', 'preco']);

        $this->actingAs($admin)->post('/admin/cupcakes', $this->dadosProduto(['preco' => 'abc']))
            ->assertSessionHasErrors('preco');

        $this->actingAs($admin)->post('/admin/cupcakes', $this->dadosProduto(['preco' => '-5']))
            ->assertSessionHasErrors('preco');

        $this->assertDatabaseCount('produtos', 0);
    }

    public function test_admin_edita_e_desativa_cupcake(): void
    {
        $admin = User::factory()->admin()->create();
        $produto = Produto::factory()->create(['nome' => 'Antigo']);

        // Sem o campo "ativo" marcado, o cupcake é desativado (RN09).
        $dados = $this->dadosProduto(['nome' => 'Renomeado']);
        unset($dados['ativo']);

        $this->actingAs($admin)->put(route('admin.produtos.update', $produto), $dados)
            ->assertRedirect(route('admin.produtos.index'));

        $produto->refresh();
        $this->assertSame('Renomeado', $produto->nome);
        $this->assertFalse($produto->ativo);
        // Limpa a mensagem de sucesso da sessão para olhar só o cardápio.
        $this->flushSession();
        $this->get('/')->assertDontSee('Renomeado');
    }
}
