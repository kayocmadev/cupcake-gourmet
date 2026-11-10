<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ProdutoSeeder::class);

        // Contas de demonstração para testar o sistema. As senhas NUNCA ficam no código:
        // defina ADMIN_PASSWORD e CLIENTE_DEMO_PASSWORD no .env ou, se não definir, uma senha
        // aleatória é gerada e mostrada uma única vez no terminal.
        $this->criarConta('Administrador', 'admin@cupcakegourmet.test', '83900000000', env('ADMIN_PASSWORD'), true);
        $this->criarConta('Cliente Exemplo', 'cliente@cupcakegourmet.test', '83911111111', env('CLIENTE_DEMO_PASSWORD'), false);
    }

    private function criarConta(string $nome, string $email, string $telefone, ?string $senha, bool $admin): void
    {
        if (User::where('email', $email)->exists()) {
            return; // não altera a senha de uma conta que já existe
        }

        $geradaAgora = blank($senha);
        $senha = $geradaAgora ? Str::password(16, symbols: false) : $senha;

        User::create([
            'name' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'password' => $senha,
            'is_admin' => $admin,
        ]);

        if ($geradaAgora) {
            $this->command?->warn("Conta criada: {$email} | senha gerada (anote, não será mostrada de novo): {$senha}");
        }
    }
}
