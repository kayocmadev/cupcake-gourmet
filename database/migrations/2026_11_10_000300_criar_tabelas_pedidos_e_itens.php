<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('status', 20)->default('recebido')->index();
            $table->decimal('taxa_entrega', 8, 2);
            $table->decimal('total', 10, 2);
            $table->string('endereco_entrega');
            $table->string('forma_pagamento', 20);
            $table->string('observacao')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        Schema::create('itens_pedido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->foreignId('produto_id')->constrained('produtos');
            $table->unsignedSmallInteger('quantidade');
            $table->decimal('preco_unitario', 8, 2);
            $table->timestamps();

            $table->unique(['pedido_id', 'produto_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itens_pedido');
        Schema::dropIfExists('pedidos');
    }
};
