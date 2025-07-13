<?php
namespace App\Domain\Model;

use Database\Factories\InstituicaoFactory;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Carbon\Carbon;

class Instituicao extends Model
{
    use HasFactory;

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

    protected static function boot(): void
    {
        parent::boot();
        
        static::addGlobalScope('nao_excluido', function (Builder $builder) {
            $builder->where('excluido', false);
        });
    }

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class, 'id_instituicao');
    }

    public function setRazaoSocialAttribute($value): void
    {
        $this->attributes['razao_social'] = strtoupper($value); 
    }

    public function setTipoLogradouroAttribute($value): void
    {
        $this->attributes['tipo_logradouro'] = strtoupper($value); 
    }

    public function getBasePath(): string
    {
        return "instituicoes/{$this->id}/imagem";
    }

    public function updateImagem(string $newPath = ''): void
    {
        $this->imagem = $newPath;
        $this->save();
    }

    public function reload(): Instituicao
    {
        return $this->refresh();
    }

    public function excluir(): bool
    {
        $this->excluido = true;
        $this->excluido_data = Carbon::now();

        return $this->save();
    }

    protected static function newFactory(): InstituicaoFactory
    {
        return InstituicaoFactory::new();
    }
}