<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_instituicao')
                  ->constrained('instituicao')
                  ->restrictOnDelete();
            $table->string('nome_curso', 150);
            $table->enum('situacao', ['A', 'I'])->default('A');
            $table->unsignedInteger('duracao_minima')->comment('Duração mínima do curso, em anos');
            $table->unsignedInteger('duracao_maxima')->comment('Duração máxima do curso, em anos');
            $table->boolean('excluido')->default(false);
            $table->date('excluido_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso');
    }
};