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
        Schema::table('grupo_estudo_publicacao', function (Blueprint $table) {
            $table->integer('qtde_curtidas')->default(0)->change();
            $table->integer('qtde_visualizacoes')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grupo_estudo_publicacao', function (Blueprint $table) {
            // Remove os defaults, caso queira reverter
            $table->integer('qtde_curtidas')->change();
            $table->integer('qtde_visualizacoes')->change();
        });
    }
};