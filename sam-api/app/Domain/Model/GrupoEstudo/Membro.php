<?php

namespace App\Domain\Model\GrupoEstudo;

use App\Domain\Model\User;

use Database\Factories\GrupoEstudo\MembroFactory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membro extends Model
{
    use HasFactory;

    protected $table = 'grupo_estudo_membro';
    
    protected $fillable = [
        'id_usuario',
        'id_grupo_estudo',
        'situacao'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function grupoEstudo(): BelongsTo
    {
        return $this->belongsTo(GrupoEstudo::class, 'id_grupo_estudo');
    }
    
    public function inativar(): bool
    {
        $this->situacao = 'I';
        return $this->save();
    }

    public function ativar(): bool
    {
        $this->situacao = 'A';
        return $this->save();
    }

    public function reload(): Membro
    {
        return $this->refresh();
    }

    protected static function newFactory(): MembroFactory
    {
        return MembroFactory::new();
    }
}
