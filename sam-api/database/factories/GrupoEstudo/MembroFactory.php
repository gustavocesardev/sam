<?php

namespace Database\Factories\GrupoEstudo;

use App\Domain\Model\GrupoEstudo\Membro;
use App\Domain\Model\User;
use App\Domain\Model\GrupoEstudo\GrupoEstudo;
use Illuminate\Database\Eloquent\Factories\Factory;

class MembroFactory extends Factory
{
    protected $model = Membro::class;

    public function definition(): array
    {
        return [
            'id_usuario' => User::factory(),
            'id_grupo_estudo' => GrupoEstudo::factory(),
            'situacao' => $this->faker->randomElement(['A', 'I']),
        ];
    }
}
