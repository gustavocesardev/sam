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
        Schema::create('grupo_estudo_publicacao_keyword', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_publicacao')
                  ->constrained('grupo_estudo_publicacao')
                  ->restrictOnDelete();
            $table->string('keyword', 250);
            $table->integer('frequencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_estudo_publicacao_keyword');
    }
};
