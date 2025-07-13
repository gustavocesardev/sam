<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('formulario', function (Blueprint $table) {
            $table->boolean('excluido')->default(false);
            $table->date('excluido_data')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('formulario', function (Blueprint $table) {
            $table->dropColumn(['excluido', 'excluido_data']);
        });
    }
};
