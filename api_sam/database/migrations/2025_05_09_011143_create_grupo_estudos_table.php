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
        Schema::create('grupo_estudo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_curso')
                  ->constrained('curso');
            $table->foreignId('id_usuario')
                  ->constrained('users');
            $table->string('nome_grupo', 150);
            $table->text('descricao');
            $table->string('hashtags', 150);
            $table->text('imagem');
            $table->text('imagem_header');
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
        Schema::dropIfExists('grupo_estudo');
    }
};
