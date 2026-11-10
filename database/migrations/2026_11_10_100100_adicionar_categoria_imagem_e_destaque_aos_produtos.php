<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->string('categoria', 30)->default('tradicionais')->after('sabor')->index();
            $table->string('imagem')->nullable()->after('preco');
            $table->boolean('destaque')->default(false)->after('ativo');
        });
    }

    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropIndex(['categoria']);
            $table->dropColumn(['categoria', 'imagem', 'destaque']);
        });
    }
};
