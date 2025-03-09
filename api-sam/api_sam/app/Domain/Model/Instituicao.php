<?php

namespace App\Domain\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Instituicao extends Model
{
    protected $table = 'instituicao';
    protected $fillable = [
        'razao_social',
        'tipo_instituicao',
        'tipo_logradouro',
        'logradouro',
        'numero',
        'cidade',
        'codigo_municipio',
        'uf',
        'dominio_email_institucional',
        'imagem',
        'excluido',
        'excluido_data'
    ];

    /**
     * Summary of boot
     * Definindo de forma global a cláusula de excluido, para não precisar definir
     * em cada repository
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope('nao_excluido', function (Builder $builder) {
            $builder->where('excluido', 'N');
        });
    }

    public function setRazaoSocialAttribute($value)
    {
        $this->attributes['razao_social'] = strtoupper($value); 
    }

    public function setTipoLogradouroAttribute($value)
    {
        $this->attributes['tipo_logradouro'] = strtoupper($value); 
    }

    /**
     * Summary of excluir
     * Excluindo, de forma lógica, o registro
     * @return bool
     */
    public function excluir(): bool
    {
        $this->excluido = 'S';
        $this->excluido_data = Carbon::now();

        return $this->save();
    }
}
