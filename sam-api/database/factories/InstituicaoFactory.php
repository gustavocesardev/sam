<?php

namespace Database\Factories;

use App\Domain\Model\Instituicao;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstituicaoFactory extends Factory
{
    protected $model = Instituicao::class;

    public function definition(): array
    {
        return [
            'razao_social' => $this->faker->company(),
            'tipo_instituicao' => $this->faker->randomElement(['PUB', 'PRI']),
            'tipo_logradouro' => 'Rua',
            'logradouro' => $this->faker->streetName(),
            'numero' => $this->faker->buildingNumber(),
            'cidade' => $this->faker->city(),
            'codigo_municipio' => $this->faker->numberBetween(1000000, 9999999),
            'uf' => $this->faker->stateAbbr(),
            'dominio_email_institucional' => $this->faker->domainName(),
            'imagem' => null,
            'excluido' => false,
            'excluido_data' => null,
        ];
    }
}
