<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE artigo_universitario ALTER COLUMN conteudo TYPE JSON USING \'[]\'::json');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE artigo_universitario ALTER COLUMN conteudo TYPE TEXT');
    }
};
