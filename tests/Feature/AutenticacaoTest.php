<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutenticacaoTest extends TestCase
{
    use RefreshDatabase;

    private function dadosCadastro(array $sobrescrever = []): array
    {
        return array_merge([
            'name' => 'Maria da Silva',
            'email' => 'maria@exemplo.com',
            'telefone' => '(83) 99999-0000',
            'password' => 'senha-segura-1',
            'password_confirmation' => 'senha-segura-1',
        ], $sobrescrever);
    }

    public function test_visitante_consegue_se_cadastrar_e_fica_autenticado(): void
    {
        $this->post('/cadastro', $this->dadosCadastro())->assertRedirect(route('cardapio.index'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'maria@exemplo.com', 'is_admin' => false]);
    }

    public function test_senha_e_gravada_com_hash(): void
    {
        $this->post('/cadastro', $this->dadosCadastro());

        $this->assertNotSame('senha-segura-1', User::first()->password);
    }

    public function test_cadastro_rejeita_email_repetido(): void
    {
        User::factory()->create(['email' => 'maria@exemplo.com']);

        $this->post('/cadastro', $this->dadosCadastro())->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 1);
    }

    public function test_cadastro_rejeita_senha_curta_ou_sem_confirmacao(): void
    {
        $this->post('/cadastro', $this->dadosCadastro(['password' => '123', 'password_confirmation' => '123']))
            ->assertSessionHasErrors('password');

        $this->post('/cadastro', $this->dadosCadastro(['password_confirmation' => 'diferente-123']))
            ->assertSessionHasErrors('password');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_cadastro_rejeita_telefone_invalido_e_campos_vazios(): void
    {
        $this->post('/cadastro', $this->dadosCadastro(['telefone' => 'abc']))
            ->assertSessionHasErrors('telefone');

        $this->post('/cadastro', [])->assertSessionHasErrors(['name', 'email', 'telefone', 'password']);
    }

    public function test_login_com_dados_corretos(): void
    {
        $usuario = User::factory()->create(['password' => 'senha-segura-1']);

        $this->post('/entrar', ['email' => $usuario->email, 'password' => 'senha-segura-1'])
            ->assertRedirect(route('cardapio.index'));

        $this->assertAuthenticatedAs($usuario);
    }

    public function test_login_com_senha_errada_mostra_mensagem_generica(): void
    {
        $usuario = User::factory()->create(['password' => 'senha-segura-1']);

        $this->from('/entrar')
            ->post('/entrar', ['email' => $usuario->email, 'password' => 'errada'])
            ->assertRedirect('/entrar')
            ->assertSessionHasErrors(['email' => 'E-mail ou senha incorretos.']);

        $this->assertGuest();
    }

    public function test_logout_encerra_a_sessao(): void
    {
        $this->actingAs(User::factory()->create())->post('/sair')->assertRedirect(route('cardapio.index'));

        $this->assertGuest();
    }

    public function test_usuario_autenticado_nao_acessa_telas_de_entrada(): void
    {
        $this->actingAs(User::factory()->create())->get('/entrar')->assertRedirect(route('cardapio.index'));
    }
}
