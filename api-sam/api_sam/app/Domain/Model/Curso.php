<?php

namespace App\Domain\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
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

    protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope('nao_excluido', function (Builder $builder) {
            $builder->where('excluido', 'N');
        });
    }

    public function setNomeCursoAttribute($value)
    {
        $this->attributes['nome_curso'] = ucfirst(strtolower($value)); 
    }

    public function excluir(): bool
    {
        $this->excluido = 'S';
        $this->excluido_data = Carbon::now();

        return $this->save();
    }
}
