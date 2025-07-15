<?php

namespace App\Application\Services;

use App\Application\Contracts\Infrastructure\CryptoServiceInterface;
use App\Application\Contracts\Infrastructure\ImageProcessorInterface;

use App\Domain\Enums\ErrorContext;
use App\Domain\Exceptions\AnoMaximoException;
use App\Domain\Exceptions\EmailException;
use App\Domain\Model\User;
use App\Domain\Policies\CursoPolicy;
use App\Domain\Policies\EmailPolicy;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\VO\Email;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private InstituicaoService $instituicaoService,
        private CursoService $cursoService,
        private ImageProcessorInterface $imageProcessor,
        private CryptoServiceInterface $cryptoService
    ) {}
    
    public function listAll(): Collection
    {
        return $this->userRepository->findAll();
    }
    
    public function find(int $id): User
    {
        return $this->userRepository->find($id);
    }

    public function findByEmail(string $email): User | null
    {
        return $this->userRepository->findByEmail($email);
    }

    public function findWithDetails(int $id): User
    {
        return $this->userRepository->findWithCountArtigoPublicacao($id);
    }

    public function store(array $data): User
    {
        $this->validarEmail($data['id_instituicao'], $data['email']);
        return $this->userRepository->store($data);
    }

    public function update(int $id, array $data): User
    {
        $user = $this->find($id);

        $this->validarAnoFimCurso(
            $user->id_curso, 
            $data['ano_inicio_curso'], 
            $data['ano_fim_curso']
        );

        $this->userRepository->update($id, $data);

        if (array_key_exists('foto_perfil', $data))
        {
            if ($data['foto_perfil'] instanceof UploadedFile)
            {
                $this->atualizarFotoDePerfil($user, $data['foto_perfil']);
                return $user->reload();
            } 

            $this->removerFotoDePerfil($user);
            return $user->reload();
        }

        return $user->reload();
    }

    public function atualizarFotoDePerfil(User $user, UploadedFile $imagem): void
    {
        $path = $this->imageProcessor->storeImage($imagem, $user->getBasePath());
        $hashPath = $this->cryptoService->encryptUrl($path);

        $user->updateFotoPerfil($hashPath);
    }

    public function removerFotoDePerfil(User $user): void
    {
        if (!empty($user->foto_perfil))
        {
            $this->imageProcessor->excluirArquivo($user->foto_perfil);
            $user->updateFotoPerfil();
        }
    }

    public function delete(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function validarEmail(int $id_instituicao, string $email): void
    {
        $this->verificarEmailEmUso($email);
        $this->verificarDominioEmail($id_instituicao, $email);
    }

    private function verificarEmailEmUso(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);

        if (!empty($user))
        {
            throw new EmailException(
                ErrorContext::REGISTER, 
                'O E-mail informado para registro já está em uso.'
            );
        }
    }

    private function verificarDominioEmail(int $id_instituicao, string $emailValue): void
    {
        $email = new Email($emailValue);
        $instituicao = $this->instituicaoService->find($id_instituicao);

        if (!EmailPolicy::pertenceDominioInstitucional($email->getDominio(), $instituicao->dominio_email_institucional))
        {
            throw new EmailException(
                ErrorContext::REGISTER,
                "O E-mail deve ser do mesmo domínio definido pela instituição: {$instituicao->dominio_email_institucional}."
            );
        }
    }

    function validarAnoFimCurso(int $id_curso, ?int $anoMin, ?int $anoMax): void
    {
        if (is_null($anoMin) || is_null($anoMax)) return; 
        
        $curso = $this->cursoService->find($id_curso);

        if (!CursoPolicy::anoFimCursoValido($anoMin, $anoMax, $curso->duracao_maxima))
        {
            throw new AnoMaximoException(
                ErrorContext::USER,
                "O total de anos informado excede o máximo de {$curso->duracao_maxima} anos definido pelo curso.",
            );
        }
    }
}