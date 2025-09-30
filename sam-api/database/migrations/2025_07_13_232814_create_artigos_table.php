<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('artigo_universitario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->string('titulo', 250);
            $table->string('palavras_chave', 250)->nullable();
            $table->text('conteudo');
            $table->string('pdf', 2048)->nullable();
            $table->boolean('excluido')->default(false);
            $table->date('excluido_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artigo_universitario');
    }
};
