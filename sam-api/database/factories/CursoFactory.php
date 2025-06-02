<?php

namespace Database\Factories;

use App\Domain\Model\Curso;
use Illuminate\Database\Eloquent\Factories\Factory;

class CursoFactory extends Factory
{
    protected $model = Curso::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cursosAleatorios = [
            'Engenharia Civil',
            'Arquitetura e Urbanismo',
            'Ciência da Computação',
            'Design Gráfico',
            'Educação Física',
            'Nutrição',
            'Farmácia',
            'Fisioterapia',
            'Biomedicina',
            'Jornalismo',
            'Publicidade e Propaganda',
            'Relações Internacionais',
            'Economia',
        ];
        
        return [
            'id_instituicao' => 1,
            'nome_curso' => $this->faker->randomElement($cursosAleatorios) ,
            'situacao' => 'A',
            'duracao_minima' => $this->faker->numberBetween(3, 4),
            'duracao_maxima' => $this->faker->numberBetween(5, 6),
        ];
    }
}
