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
        Schema::create('publicacao_visualizacao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_publicacao')
                  ->constrained('publicacao')
                  ->restrictOnDelete();
            $table->foreignId('id_usuario')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicacao_visualizacao');
    }
};
