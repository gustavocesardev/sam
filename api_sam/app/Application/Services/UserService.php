<?php

namespace App\Application\Services;

use App\Application\Contracts\ImageProcessorInterface;
use App\Domain\Enums\ErrorContext;

use App\Domain\Exceptions\AnoMaximoException;
use App\Domain\Exceptions\EmailException;

use App\Domain\Model\User;

use App\Domain\Policies\CursoPolicy;
use App\Domain\Policies\EmailPolicy;

use App\Domain\Repository\UserRepositoryInterface;

use App\Domain\VO\Email;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private InstituicaoService $instituicaoService,
        private CursoService $cursoService,
        private ImageProcessorInterface $imageProcessor
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

        // TODO: Caso não mandar nenhuma imagem, tratar como se tivesse excluindo a antiga
        if (!empty($data['foto_perfil']) && $data['foto_perfil'] != $user->foto_perfil)
        {
            $this->atualizarFotoDePerfil($user, $data['foto_perfil'] );
        }

        return $user->refresh();
    }

    public function atualizarFotoDePerfil(User $user, UploadedFile $imagem): void
    {
        $idInstituicao = $user->curso->id_instituicao;
        $idCurso = $user->curso->id;

        Storage::disk('public')->delete($user->foto_perfil);

        $path = $this->imageProcessor->storeUserProfileImage($imagem, "instituicoes/{$idInstituicao}/cursos/{$idCurso}/users/{$user->id}/profile");

        $user->updateFotoPerfil($path);
        $this->userRepository->save($user);
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
                ErrorContext::CADASTRO_USER,
                "O total de anos informado excede o máximo de {$curso->duracao_maxima} anos definido pelo curso.",
            );
        }
    }
}