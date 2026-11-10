<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ProdutoSeeder::class);

        // Contas de demonstração para testar o sistema (apenas ambiente de desenvolvimento).
        User::updateOrCreate(
            ['email' => 'admin@cupcakegourmet.test'],
            ['name' => 'Administrador', 'telefone' => '83900000000', 'password' => '[senha-removida]', 'is_admin' => true],
        );

        User::updateOrCreate(
            ['email' => 'cliente@cupcakegourmet.test'],
            ['name' => 'Cliente Exemplo', 'telefone' => '83911111111', 'password' => '[senha-removida]'],
        );
    }
}
