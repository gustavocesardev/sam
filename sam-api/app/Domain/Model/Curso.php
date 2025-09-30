<?php

namespace App\Domain\Model;

use Database\Factories\CursoFactory;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Carbon\Carbon;

class Curso extends Model
{
    use HasFactory;
    
    protected $table = 'curso';

    protected $fillable = [
        'id_instituicao',
        'nome_curso',
        'situacao',
        'duracao_minima',
        'duracao_maxima',
        'excluido',
        'excluido_data'
    ];

    protected $casts = [
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

    public function instituicao(): BelongsTo
    {
        return $this->belongsTo(Instituicao::class, 'id_instituicao');
    }

    public function setNomeCursoAttribute($value): void
    {
        $this->attributes['nome_curso'] = ucfirst(strtolower($value)); 
    }

    public function excluir(): bool
    {
        $this->excluido = true;
        $this->excluido_data = Carbon::now();

        return $this->save();
    }

    protected static function newFactory(): CursoFactory
    {
        return CursoFactory::new();
    }
}
