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
        Schema::create('grupo_estudo_publicacao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_membro')
                  ->constrained('grupo_estudo_membro');
            $table->foreignId('id_publicacao_vinculada')
                  ->nullable()
                  ->constrained('grupo_estudo_publicacao')
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
        Schema::dropIfExists('grupo_estudo_publicacao');
    }
};
