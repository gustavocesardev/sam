<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\Instituicao;
use App\Domain\Repository\InstituicaoRepositoryInterface;

use Illuminate\Support\Collection;

class InstituicaoRepository implements InstituicaoRepositoryInterface
{
    public function findAll(): Collection
    {  
        return Instituicao::all();
    }

    public function find(int $id): Instituicao
    {
        return Instituicao::findOrFail($id);
    }

    public function findByDominio(string $dominio): ?Instituicao
    {
        return Instituicao::where('dominio_email_institucional', $dominio)->first();
    }

    public function store(array $data): Instituicao
    {
        return Instituicao::create($data);
    }

    public function update(int $id, array $data): Instituicao
    {
        $instituicao = $this->find($id);
        $instituicao->fill($data)->save();
        $instituicao->refresh();

        return $instituicao;
    }

    public function delete(int $id): bool
    {
        $instituicao = $this->find($id);
        return $instituicao->excluir();
    }
}
