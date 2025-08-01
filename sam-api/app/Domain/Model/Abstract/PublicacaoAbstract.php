<?php

namespace App\Domain\Model\Abstract;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

abstract class PublicacaoAbstract extends Model
{
    public const FIELDS = [
        'id_publicacao_vinculada',
        'texto',
        'imagens',
        'qtde_curtidas',
        'qtde_visualizacoes',
        'excluido',
        'excluido_data'
    ];
    protected $fillable = self::FIELDS;

    protected $casts = [
        'imagens' => 'array',
        'excluido' => 'boolean',
        'excluido_data' => 'date',
    ];

    public function updateImagens(array $newPaths): void
    {
        $this->imagens = $newPaths;
        $this->save();
    }

    public function excluir(): bool
    {
        $this->excluido = true;
        $this->excluido_data = Carbon::now();

        return $this->save();
    }

    public function reload(): PublicacaoAbstract
    {
        return $this->refresh()->loadCount(['publicacoesVinculadas as qtde_comentarios']);
    }

    public function adicionarReacao(): void
    {
        $this->qtde_curtidas += 1;
        $this->save();
    }

    public function removerReacao(): void
    {
        if ($this->qtde_curtidas > 0)
        {
            $this->qtde_curtidas -= 1;
            $this->save();
        }
    }

    public function adicionarVisualizacao(): void
    {
        $this->qtde_visualizacoes += 1;
        $this->save();
    }

    public abstract function getBasePath(): string;
    public abstract function getIdUsuario(): int;
}