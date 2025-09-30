<?php

namespace Database\Seeders;

use App\Domain\Model\Instituicao;
use Illuminate\Database\Seeder;
use App\Domain\Model\Curso;
use App\Domain\Model\User;
use App\Domain\Model\Publicacao\Publicacao;
use App\Domain\Model\GrupoEstudo\GrupoEstudo;
use App\Domain\Model\GrupoEstudo\Membro;
use App\Domain\Model\GrupoEstudo\Publicacao as GrupoEstudoPublicacao;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $instituicoes = Instituicao::factory()->count(3)->create();

        // Criar 5 cursos
        $cursos = Curso::factory()->count(5)->state(fn () => [
            'id_instituicao' => $instituicoes->random()->id,
        ])->create();

        // Criar 20 usuários
        $usuarios = User::factory()->count(20)->create();

        // Criar 50 publicações avulsas (fora de grupo)
        Publicacao::factory()
            ->count(50)
            ->state(fn () => [
                'id_usuario' => $usuarios->random()->id,
            ])
            ->create();

        // Criar 10 grupos de estudo
        $grupos = GrupoEstudo::factory()
            ->count(10)
            ->state(fn () => [
                'id_usuario' => $usuarios->random()->id,
                'id_curso' => $cursos->random()->id,
            ])
            ->create();

        foreach ($grupos as $grupo) {
            // Criar 5 membros por grupo
            $membros = Membro::factory()
                ->count(5)
                ->state(fn () => [
                    'id_grupo_estudo' => $grupo->id,
                    'id_usuario' => $usuarios->random()->id,
                    'situacao' => 'A',
                ])
                ->create();

            foreach ($membros as $membro) {
                // Criar 3 publicações por membro
                GrupoEstudoPublicacao::factory()
                    ->count(3)
                    ->state(fn () => [
                        'id_membro' => $membro->id,
                    ])
                    ->create();
            }
        }
    }
}