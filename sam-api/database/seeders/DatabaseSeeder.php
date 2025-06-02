<?php

namespace Database\Seeders;

use App\Domain\Model\Curso;
use App\Domain\Model\Publicacao\Publicacao;
use App\Domain\Model\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar 5 cursos
        Curso::factory()->count(5)->create();

        // Criar 20 usuários
        $usuarios = User::factory()->count(20)->create();

        // Criar 50 publicações
        Publicacao::factory()
            ->count(50)
            ->state(fn () => [
                'id_usuario' => $usuarios->random()->id,
            ])
            ->create();
        }
}
