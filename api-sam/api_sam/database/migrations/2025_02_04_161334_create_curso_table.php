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
        Schema::create('curso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_instituicao')
                  ->constrained('instituicao')
                  ->restrictOnDelete();
            $table->string('nome_curso', 150);
            $table->enum('situacao', ['A', 'I'])->default('A');
            $table->integer('duracao_minima', unsigned: true)->unsigned()->comment('Duração mínima do curso, em anos');
            $table->integer('duracao_maxima', unsigned: true)->unsigned()->comment('Duração máxima do curso, em anos');
            $table->enum('excluido', ['S', 'N'])->default('N');
            $table->date('excluido_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso');
    }
};
