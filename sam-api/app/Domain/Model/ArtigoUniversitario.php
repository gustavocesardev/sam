<?php

namespace App\Domain\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtigoUniversitario extends Model
{
    protected $table = 'artigo_universitario';

    protected $fillable = [
        'id_usuario',
        'titulo',
        'palavras_chave',
        'conteudo',
        'pdf'
    ];

    protected $casts = [
        'conteudo' => 'array',
        'excluido' => 'boolean',
        'excluido_data' => 'date'
    ];

    protected static function boot(): void
    {
        parent::boot();
        
        static::addGlobalScope('nao_excluido', function (Builder $builder) {
            $builder->where('excluido', false);
        });
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function getBasePath(): string
    {
        return "instituicoes/{$this->usuario->curso->id_instituicao}/cursos/{$this->usuario->curso->id}/users/{$this->usuario->id}/artigos_universitarios";
    }

    public function excluir(): bool
    {
        $this->excluido = true;
        $this->excluido_data = Carbon::now();

        return $this->save();
    }

    public function updatePdf(string $newPath = ''): void
    {
        $this->pdf = $newPath;
        $this->save();
    }

    public function reload(): ArtigoUniversitario
    {
        return $this->refresh();
    }
}
