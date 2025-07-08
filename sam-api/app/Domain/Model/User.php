<?php

namespace App\Domain\Model;

use App\Domain\Model\GrupoEstudo\GrupoEstudo;
use App\Domain\Model\GrupoEstudo\Membro;

use Database\Factories\UserFactory;
use Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'id_curso',
        'name',
        'email',
        'email_verified_at',
        'password',
        'foto_perfil',
        'biografia',
        'ano_inicio_curso',
        'ano_fim_curso',
        'situacao',
        'excluido',
        'excluido_data'
    ];

    protected $hidden = [
        'email_verified_at',
        'password',
        'remember_token',
        'excluido',
        'excluido_data'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'excluido' => 'boolean',
            'excluido_data' => 'date',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        
        static::addGlobalScope('nao_excluido', function (Builder $builder) {
            $builder->where('excluido', false);
        });
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }

    public function gruposEstudo(): HasMany
    {
        return $this->hasMany(GrupoEstudo::class, 'id_usuario');
    }

    public function membrosGrupoEstudo(): HasMany
    {
        return $this->hasMany(Membro::class, 'id_usuario');
    }

    public function getBasePath(): string
    {
        return "instituicoes/{$this->curso->id_instituicao}/cursos/{$this->curso->id}/users/{$this->id}/profile";
    }

    public function excluir(): bool
    {
        $this->excluido = true;
        $this->excluido_data = Carbon::now();

        return $this->save();
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

    public function updateFotoPerfil(string $newPath = ''): void
    {
        $this->foto_perfil = $newPath;
        $this->save();
    }

    public function reload(): User
    {
        return $this->refresh();
    }

    public function verificarEmailHash(string $hash): bool
    {
        return hash_equals($hash, sha1($this->getEmailForVerification()));
    }

    public function verificarSenha(string $senha): bool
    {
        return Hash::check($senha, $this->password);
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}