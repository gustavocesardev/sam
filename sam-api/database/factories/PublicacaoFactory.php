<?php

namespace Database\Factories;

use App;

use App\Domain\Services\KeywordService;
use App\Domain\Model\Publicacao\Publicacao;
use App\Domain\Model\User;

use Illuminate\Database\Eloquent\Factories\Factory;

class PublicacaoFactory extends Factory
{  
    protected $model = Publicacao::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $disciplinas = [
            'Cálculo', 'Programação Orientada a Objetos', 'Banco de Dados', 
            'Redes de Computadores', 'Sistemas Operacionais', 'Análise de Algoritmos',
            'Engenharia de Software', 'Fisiologia', 'Anatomia', 'Direito Constitucional',
            'Marketing Digital', 'Finanças Corporativas', 'Estatística Aplicada',
            'Filosofia', 'Sociologia', 'Psicologia Social', 'Antropologia Cultural',
            'Design de Interface', 'Inteligência Artificial', 'Machine Learning',
            'Química Orgânica', 'Biologia Molecular', 'Administração Estratégica',
            'Comunicação Social', 'Educação Ambiental'
        ];

        $assuntos = [
            'a prova final', 'o trabalho do semestre', 'a aula prática',
            'a apresentação do projeto', 'o artigo científico',
            'o seminário de conclusão', 'o laboratório experimental',
            'a revisão bibliográfica', 'a defesa do TCC', 'a pesquisa de campo'
        ];

        $perguntas = [
            "Alguém tem material para revisar %s?",
            "Quem topa formar grupo para estudar %s?",
            "Dicas para mandar bem em %s?",
            "Alguém já fez o exercício sobre %s?",
            "Como estão os preparativos para %s?",
            "Alguém recomenda livro para %s?",
            "Qual a melhor estratégia para entender %s?",
            "Existe algum tutorial bom para %s?",
            "Alguém sabe onde consigo exemplos práticos de %s?",
            "Quem pode ajudar com dúvidas em %s?"
        ];

        // Sorteia se vai preencher com disciplina ou assunto
        $usarDisciplina = $this->faker->boolean(70); // 70% disciplina, 30% assunto

        $tema = $usarDisciplina
            ? $this->faker->randomElement($disciplinas)
            : $this->faker->randomElement($assuntos);

        $template = $this->faker->randomElement($perguntas);

        $texto = sprintf($template, $tema);

        return [
            'id_usuario' => User::factory(),
            'id_publicacao_vinculada' => null,
            'texto' => $texto,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($publicacao) {
            // Instancia o serviço (pode usar App::make para resolver dependências)
            $keywordService = App::make(KeywordService::class);

            // Chama o método para extrair keywords com frequência do texto da publicação
            $keywords = $keywordService->extractWithFrequency($publicacao->texto);

            foreach ($keywords as $keyword => $frequency) {
                // Insere no banco as keywords associadas à publicação
                $publicacao->keywords()->create([
                    'keyword' => $keyword,
                    'frequencia' => $frequency,
                ]);
            }
        });
    }
}
