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
        Schema::create('grupo_estudo_publicacao_reacao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_publicacao')
                  ->constrained('grupo_estudo_publicacao')
                  ->restrictOnDelete();
            $table->foreignId('id_membro')
                  ->constrained('grupo_estudo_membro')
                  ->restrictOnDelete();
            $table->enum('situacao', ['A', 'I'])->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_estudo_publicacao_reacao');
    }
};
