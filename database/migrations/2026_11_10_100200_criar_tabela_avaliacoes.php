<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 120);
            $table->date('data_teste');
            $table->string('aparelho', 120)->nullable();
            $table->text('funcionou')->nullable();
            $table->text('nao_funcionou')->nullable();
            $table->text('nao_testado')->nullable();
            $table->text('sugestoes')->nullable();
            $table->unsignedTinyInteger('nota')->nullable();
            $table->string('captura')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};
