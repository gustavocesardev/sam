<?php

namespace App\Domain\Model\Abstract;

use App\Domain\Model\User;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

abstract class AbstractPublicacao extends Model
{
    public function updateImagens(array $newPaths): void
    {
        $this->imagens = $newPaths;
    }

    public function excluir(): bool
    {
        $this->excluido = true;
        $this->excluido_data = Carbon::now();

        return $this->save();
    }

    public function adicionarReacao(): void
    {
        $this->qtde_curtidas += 1;
        $this->save();
    }

    public function removerReacao(): void
    {
        if ($this->qtde_curtidas > 0)
        {
            $this->qtde_curtidas -= 1;
            $this->save();
        }
    }

    public abstract function getBaseImagePath(User $user): string;
    public abstract function getIdUsuario(): int;
}