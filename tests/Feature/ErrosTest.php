<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrosTest extends TestCase
{
    use RefreshDatabase;

    public function test_pagina_inexistente_mostra_tela_de_erro_amigavel(): void
    {
        $this->get('/pagina-que-nao-existe')
            ->assertNotFound()
            ->assertSee('Página não encontrada')
            ->assertSee('Voltar ao cardápio');
    }

    public function test_cupcake_inexistente_mostra_tela_de_erro_amigavel(): void
    {
        $this->get('/cupcakes/999999')->assertNotFound()->assertSee('Página não encontrada');
    }
}
