<?php

namespace App\Application\Services;

use App\Domain\Enums\ErrorContext;
use App\Domain\Exceptions\DuplicateEntryException;
use App\Domain\Model\Instituicao;
use App\Domain\Repository\InstituicaoRepositoryInterface;
use Illuminate\Support\Collection;

class InstituicaoService
{
    public function __construct(private InstituicaoRepositoryInterface $instituicaoRepository) {}

    /**
     * TODO: Adicionar paginação
     * Summary of listAll
     * @return Collection
     */
    public function listAll(): Collection
    {
        return $this->instituicaoRepository->findAll();
    }

    public function store(array $data): Instituicao
    {
        $this->validarDuplicidade(null, $data);
        return $this->instituicaoRepository->store($data);
    }

    public function find(int $id): Instituicao
    {
        return $this->instituicaoRepository->find($id);
    }

    public function update(int $id, array $data): Instituicao
    {
        $this->validarDuplicidade($id, $data);
        return $this->instituicaoRepository->update($id, $data);
    }

    public function delete(int $id): void
    {
        $this->instituicaoRepository->delete($id);
    }

    private function validarDuplicidade(?int $id = null, array $data): void
    {
        $instituicao = $this->instituicaoRepository->findByDominio($data['dominio_email_institucional']);

        if ($instituicao && $instituicao->id != $id)
        {
            throw new DuplicateEntryException(
                'dominio_email_institucional',
                ErrorContext::CADASTRO_INSTITUICAO
            );
        }
    }
}
