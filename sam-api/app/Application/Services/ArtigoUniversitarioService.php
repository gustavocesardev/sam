<?php

namespace App\Application\Services;

use App\Application\Contracts\Infrastructure\CryptoServiceInterface;
use App\Application\Contracts\Infrastructure\DocumentProcessorInterface;
use App\Domain\Enums\ErrorContext;
use App\Domain\Exceptions\AppException;
use App\Domain\Model\ArtigoUniversitario;
use App\Domain\Repository\ArtigoUniversitarioRepositoryInterface;

use App\Domain\VO\Auth\AuthenticatedUser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class ArtigoUniversitarioService
{
    public function __construct(
        private ArtigoUniversitarioRepositoryInterface $artigoUniversitarioRepository,
        private DocumentProcessorInterface $documentProcessor,
        private CryptoServiceInterface $cryptoService
    ) {}

    public function find(int $id): ArtigoUniversitario
    {
        return $this->artigoUniversitarioRepository->find($id);
    }

    public function store(array $data): ArtigoUniversitario
    {
        if (isset($data['conteudo']) && is_string($data['conteudo']))
        {
            $decoded = json_decode($data['conteudo'], true);

            if ($decoded === null && json_last_error() !== JSON_ERROR_NONE)
            {
                throw new AppException(
                    'O atributo conteudo não é um JSON válido.',
                    ErrorContext::ARTIGO,
                    500
                );
            }

            $data['conteudo'] = $decoded;
        }

        $artigoUniversitario = $this->artigoUniversitarioRepository->store($data);

        if (array_key_exists('pdf', $data))
        {  
            if ($data['pdf'] instanceof UploadedFile)
            {
                $this->atualizarPdf($artigoUniversitario, $data['pdf']);
                return $artigoUniversitario->reload();
            } 

            $this->removerPdf($artigoUniversitario);
            return $artigoUniversitario->reload();
        }

        return $artigoUniversitario->reload();
    }

    public function update(int $id, array $data): ArtigoUniversitario
    {
        if (isset($data['conteudo']) && is_string($data['conteudo']))
        {
            $decoded = json_decode($data['conteudo'], true);

            if ($decoded === null && json_last_error() !== JSON_ERROR_NONE)
            {
                throw new AppException(
                    'O atributo conteudo não é um JSON válido.',
                    ErrorContext::ARTIGO,
                    500
                );
            }

            $data['conteudo'] = $decoded;
        }
        
        $artigoUniversitario = $this->artigoUniversitarioRepository->update($id, $data);

        if (array_key_exists('pdf', $data))
        { 
            if ($data['pdf'] instanceof UploadedFile)
            {
                $this->atualizarPdf($artigoUniversitario, $data['pdf']);
                return $artigoUniversitario->reload();
            }

            $this->removerPdf($artigoUniversitario);
            return $artigoUniversitario->reload();
        }

        return $artigoUniversitario->reload();
    }

    private function atualizarPdf(ArtigoUniversitario $artigoUniversitario, UploadedFile $document): void
    {
        $path = $this->documentProcessor->storeDocument($document, $artigoUniversitario->getBasePath());
        $hashPath = $this->cryptoService->encryptUrl($path);

        $artigoUniversitario->updatePdf($hashPath);
    }

    public function delete(int $id): void
    {
        $artigoUniversitario = $this->find($id);
        $this->documentProcessor->excluirDiretorio($artigoUniversitario->getBasePath());
        
        $this->artigoUniversitarioRepository->delete($id);
    }

    private function removerPdf(ArtigoUniversitario $artigoUniversitario): void
    {
        if (!empty($artigoUniversitario->pdf))
        {
            $this->documentProcessor->excluirArquivo($artigoUniversitario->pdf);
            $artigoUniversitario->updatePdf();
        }
    }

    public function filtrar(AuthenticatedUser $user, array $filtros, int $limite = 15, int $page = 1): Collection
    {
        return $this->artigoUniversitarioRepository->filtrarPorCampos(
            $user->getIdInstituicao(), 
            $filtros, 
            $limite, 
            $page
        );
    }

    public function artigosUsuario(AuthenticatedUser $user, int $limite = 15, int $page = 1): Collection
    {
        return $this->artigoUniversitarioRepository->findByUsuario(
            $user->id(),
            $limite,
            $page
        );
    }
}