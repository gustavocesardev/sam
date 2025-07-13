<?php

namespace App\Domain\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Formulario extends Model
{
    use HasFactory;

    protected $table = 'formulario';

    public $timestamps = true;

    protected $fillable = [
        'id_usuario',
        'titulo',
        'descricao',
        'tipo',
        'situacao',
        'link_forms',
        'data_limite'
    ];

    protected $casts = [
        'data_limite' => 'date',
    ];

    protected static function boot(): void
    {
        parent::boot();
        
        static::addGlobalScope('nao_excluido', function (Builder $builder) {
            $builder->where('excluido', false);
        });
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function excluir(): bool
    {
        $this->excluido = true;
        $this->excluido_data = Carbon::now();

        return $this->save();
    }
}
