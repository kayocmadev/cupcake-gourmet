<?php

namespace Tests\Feature;

use App\Models\Avaliacao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AvaliacaoTest extends TestCase
{
    use RefreshDatabase;

    private function dados(array $sobrescrever = []): array
    {
        return array_merge([
            'nome' => 'Joana Testadora',
            'data_teste' => now()->format('Y-m-d'),
            'aparelho' => 'Celular Android, Chrome',
            'funcionou' => 'Criei a conta e finalizei um pedido.',
            'nao_funcionou' => 'O botão de cancelar demorou a aparecer.',
            'nao_testado' => 'Não testei o painel da loja.',
            'sugestoes' => 'Mostrar fotos maiores.',
            'nota' => 4,
        ], $sobrescrever);
    }

    public function test_pagina_do_formulario_abre_sem_login(): void
    {
        $this->get('/avaliar')->assertOk()->assertSee('Avalie o Cupcake Gourmet');
    }

    public function test_qualquer_pessoa_envia_avaliacao_sem_conta(): void
    {
        $this->post('/avaliar', $this->dados())->assertSessionHas('sucesso');

        $this->assertDatabaseHas('avaliacoes', ['nome' => 'Joana Testadora', 'nota' => 4]);
    }

    public function test_nome_e_data_sao_obrigatorios(): void
    {
        $this->post('/avaliar', [])->assertSessionHasErrors(['nome', 'data_teste'], errorBag: 'avaliacao');

        $this->assertDatabaseCount('avaliacoes', 0);
    }

    public function test_data_futura_e_nota_invalida_sao_rejeitadas(): void
    {
        $this->post('/avaliar', $this->dados(['data_teste' => now()->addDays(3)->format('Y-m-d')]))
            ->assertSessionHasErrors('data_teste', errorBag: 'avaliacao');

        $this->post('/avaliar', $this->dados(['nota' => 9]))
            ->assertSessionHasErrors('nota', errorBag: 'avaliacao');

        $this->assertDatabaseCount('avaliacoes', 0);
    }

    public function test_captura_de_tela_e_gravada_e_arquivo_invalido_e_rejeitado(): void
    {
        Storage::fake('uploads');

        $this->post('/avaliar', $this->dados(['captura' => UploadedFile::fake()->image('erro.png')]))
            ->assertSessionHas('sucesso');

        $avaliacao = Avaliacao::first();
        $this->assertNotNull($avaliacao->captura);
        Storage::disk('uploads')->assertExists($avaliacao->captura);

        $this->post('/avaliar', $this->dados(['captura' => UploadedFile::fake()->create('virus.pdf', 100, 'application/pdf')]))
            ->assertSessionHasErrors('captura', errorBag: 'avaliacao');

        $this->assertDatabaseCount('avaliacoes', 1);
    }

    public function test_admin_ve_as_avaliacoes_e_baixa_o_pdf(): void
    {
        Avaliacao::create(['nome' => 'Joana Testadora', 'data_teste' => now(), 'nota' => 5, 'funcionou' => 'Tudo certo.']);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/avaliacoes')
            ->assertOk()
            ->assertSee('Joana Testadora')
            ->assertSee('Tudo certo.');

        $this->actingAs($admin)->get('/admin/avaliacoes/pdf')
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_cliente_comum_nao_ve_as_avaliacoes(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin/avaliacoes')->assertForbidden();
        $this->actingAs(User::factory()->create())->get('/admin/avaliacoes/pdf')->assertForbidden();
    }
}
