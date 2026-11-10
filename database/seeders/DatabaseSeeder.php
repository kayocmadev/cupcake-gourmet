<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ProdutoSeeder::class);

        // Contas de demonstração para testar o sistema. Em produção, defina ADMIN_PASSWORD e
        // CLIENTE_DEMO_PASSWORD no .env; sem isso valem as senhas de desenvolvimento abaixo.
        User::updateOrCreate(
            ['email' => 'admin@cupcakegourmet.test'],
            ['name' => 'Administrador', 'telefone' => '83900000000', 'password' => env('ADMIN_PASSWORD') ?: '[senha-removida]', 'is_admin' => true],
        );

        User::updateOrCreate(
            ['email' => 'cliente@cupcakegourmet.test'],
            ['name' => 'Cliente Exemplo', 'telefone' => '83911111111', 'password' => env('CLIENTE_DEMO_PASSWORD') ?: '[senha-removida]'],
        );
    }
}
