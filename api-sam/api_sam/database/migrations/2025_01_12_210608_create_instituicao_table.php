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
        Schema::create('instituicao', function (Blueprint $table) {
            $table->id();
            $table->string('razao_social', 70);
            $table->enum('tipo_instituicao', ['PUB', 'PRI']);
            $table->string('tipo_logradouro', 100);
            $table->string('logradouro', 100);
            $table->integer('numero')->unsigned();
            $table->string('cidade', 70);
            $table->integer('codigo_municipio')->unsigned();
            $table->char('uf', 2);
            $table->string('dominio_email_institucional', 100);
            $table->string('imagem', 150)->nullable();
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
        Schema::dropIfExists('instituicao');
    }
};
