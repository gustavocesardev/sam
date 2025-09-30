<?php

namespace Database\Factories\GrupoEstudo;

use App\Domain\Model\GrupoEstudo\GrupoEstudo;
use App\Domain\Model\Curso;
use App\Domain\Model\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GrupoEstudoFactory extends Factory
{
    protected $model = GrupoEstudo::class;

    public function definition(): array
    {
        return [
            'id_usuario' => User::factory(),
            'id_curso' => Curso::factory(),
            'nome_grupo' => $this->faker->words(3, true),
            'descricao' => $this->faker->paragraph(),
            'hashtags' => implode(',', $this->faker->words(5)),
            'imagem' => '',
            'imagem_header' => '',
        ];
    }
}
