<?php

namespace App\Domain\Model\GrupoEstudo;

use App\Domain\Model\Curso;
use App\Domain\Model\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Carbon\Carbon;

class GrupoEstudo extends Model
{
    protected $table = 'grupo_estudo';
    
    protected $fillable = [
        'id_curso',
        'id_usuario',
        'nome_grupo',
        'descricao',
        'hashtags',
        'imagem',
        'imagem_header',
        'excluido',
        'excluido_data'
    ];

    protected $casts = [
        'excluido' => 'boolean',
        'excluido_data' => 'date',
    ];

    protected static function boot(): void
    {
        parent::boot();
        
        static::addGlobalScope('nao_excluido', function (Builder $builder) {
            $builder->where('excluido', false);
        });
    }

    public function getBasePath(): string
    {
        return "instituicoes/{$this->curso->id_instituicao}/cursos/{$this->curso->id}/grupos_estudos/{$this->id}";
    }

    public function getImagePath(): string
    {
        return "{$this->getBasePath()}/imagem";
    }

    public function getImageHeaderPath(): string
    {
        return "{$this->getBasePath()}/header";
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }

    public function updateImagem(string $newPath = ''): void
    {
        $this->imagem = $newPath;
        $this->save();
    }

    public function updateImagemHeader(string $newPath = ''): void
    {
        $this->imagem_header = $newPath;
        $this->save();
    }

    public function excluir(): bool
    {
        $this->excluido = true;
        $this->excluido_data = Carbon::now();

        return $this->save();
    }

    public function reload(): GrupoEstudo
    {
        return $this->refresh();
    }
}
