<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('publicacao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')
                  ->constrained('users');
            $table->foreignId('id_publicacao_vinculada')
                  ->nullable()
                  ->constrained('publicacao')
                  ->nullOnDelete();
            $table->string('texto', 500);
            $table->json('imagens')->nullable();
            $table->integer('qtde_curtidas');
            $table->integer('qtde_visualizacoes');
            $table->boolean('excluido')->default(false);
            $table->date('excluido_data')->nullable();
            $table->timestamps();
        });      
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicacao');
    }
};
