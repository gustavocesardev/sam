<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->integer('ano_inicio_curso', false, true)->length(4)->nullable()->change();
            $table->integer('ano_fim_curso', false, true)->length(4)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
 
            $table->integer('ano_inicio_curso', false, true)->length(4)->nullable(false)->change();
            $table->integer('ano_fim_curso', false, true)->length(4)->nullable(false)->change();
        });
    }
};
