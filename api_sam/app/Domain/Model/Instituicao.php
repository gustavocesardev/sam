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

    protected $casts = [
        'excluido' => 'boolean',
        'excluido_data' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope('nao_excluido', function (Builder $builder) {
            $builder->where('excluido', false);
        });
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'id_instituicao');
    }

    public function setRazaoSocialAttribute($value)
    {
        $this->attributes['razao_social'] = strtoupper($value); 
    }

    public function setTipoLogradouroAttribute($value)
    {
        $this->attributes['tipo_logradouro'] = strtoupper($value); 
    }

    public function getBasePath(): string
    {
        return "instituicoes/{$this->id}/imagem";
    }

    public function excluir(): bool
    {
        $this->excluido = true;
        $this->excluido_data = Carbon::now();

        return $this->save();
    }
}