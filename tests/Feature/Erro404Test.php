<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Erro404Test extends TestCase
{
    use RefreshDatabase;

    public function test_404_reconhece_usuario_logado(): void
    {
        $usuario = User::factory()->create(['name' => 'Maria Souza']);

        $this->actingAs($usuario)->get('/pagina-que-nao-existe')
            ->assertNotFound()
            ->assertSee('Olá, Maria')
            ->assertDontSee('Criar conta');
    }

    public function test_fuso_horario_e_de_brasilia(): void
    {
        $this->assertSame('America/Sao_Paulo', config('app.timezone'));
    }
}
