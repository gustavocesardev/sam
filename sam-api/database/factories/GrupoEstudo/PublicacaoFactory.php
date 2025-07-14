<?php

namespace Database\Factories\GrupoEstudo;

use App;
use App\Domain\Model\GrupoEstudo\Publicacao;
use App\Domain\Model\GrupoEstudo\Membro;
use App\Domain\Services\Recomendacao\GrupoEstudo\KeywordService;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicacaoFactory extends Factory
{
    protected $model = Publicacao::class;

    public function definition(): array
    {
        $temas = [
            'a prova final', 'o trabalho do semestre', 'a aula prática',
            'a apresentação do projeto', 'o artigo científico'
        ];

        $perguntas = [
            "Quem vai apresentar %s?",
            "Alguém tem material para revisar %s?",
            "Como foi a explicação sobre %s?",
            "Existe algum resumo bom para %s?",
            "Vamos marcar reunião sobre %s?"
        ];

        $template = $this->faker->randomElement($perguntas);
        $texto = sprintf($template, $this->faker->randomElement($temas));

        return [
            'id_membro' => Membro::factory(),
            'id_publicacao_vinculada' => null,
            'texto' => $texto,
            'imagens' => [],
            'qtde_curtidas' => 0,
            'qtde_visualizacoes' => 0,
            'excluido' => false,
            'excluido_data' => null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($publicacao) {
            $keywordService = App::make(KeywordService::class);
            $keywords = $keywordService->extractWithFrequency($publicacao->texto);

            foreach ($keywords as $keyword => $frequency) {
                $publicacao->keywords()->create([
                    'keyword' => $keyword,
                    'frequencia' => $frequency,
                ]);
            }
        });
    }
}
