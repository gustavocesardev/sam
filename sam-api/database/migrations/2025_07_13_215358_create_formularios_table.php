<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('formulario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->string('titulo', 100);
            $table->text('descricao');
            $table->enum('tipo', ['AC', 'GE'])->default('GE');
            $table->enum('situacao', ['A', 'I'])->default('A');
            $table->string('link_forms', 500);
            $table->date('data_limite');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formulario');
    }
};
