<?php

namespace App\Application\Services;

use App\Domain\Enums\ErrorContext;

use App\Domain\Exceptions\AnoMaximoException;
use App\Domain\Exceptions\DuplicateEntryException;
use App\Domain\Exceptions\EmailException;

use App\Domain\Model\User;

use App\Domain\Repository\CursoRepositoryInterface;
use App\Domain\Repository\InstituicaoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

use App\Domain\VO\Email;

use Illuminate\Support\Collection;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private InstituicaoRepositoryInterface $instituicaoRepository,
        private CursoRepositoryInterface $cursoRepository
    ) {}
    
    /**
     * TODO: Adicionar paginação
     * Summary of listAll
     * @return Collection
     */
    public function listAll(): Collection
    {
        return $this->userRepository->findAll();
    }
    
    public function find(int $id): User
    {
        return $this->userRepository->find($id);
    }

    public function store(array $data): User
    {
        $this->validarDuplicidade(null, $data);
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

        return $this->userRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function findByEmail(string $email): User | null
    {
        return $this->userRepository->findByEmail($email);
    }

    private function validarDuplicidade(?int $id = null, array $data): void
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if ($user && $user->id != $id)
        {
            throw new DuplicateEntryException(
                'email',
                ErrorContext::CADASTRO_USER
            );
        }
    }

    public function validarEmail(int $id_instituicao, string $email): void
    {
        $this->verificarEmailEmUso($email);
        $this->verificarDominioEmail($id_instituicao, $email);
    }

    private function verificarEmailEmUso(string $email): void
    {
        $emailExist = $this->userRepository->findByEmail($email);

        if (!is_null($emailExist))
        {
            throw new EmailException(
                ErrorContext::REGISTER, 
                'O E-mail informado para registro já está em uso.', 
            );
        }
    }

    private function verificarDominioEmail(int $id_instituicao, string $emailValue): void
    {
        $email = new Email($emailValue);

        $instituicao = $this->instituicaoRepository->find($id_instituicao);
        $emailDomain = $email->getDominio();

        if ($emailDomain !== $instituicao->dominio_email_institucional)
        {
            throw new EmailException(
                ErrorContext::REGISTER,
                "O E-mail deve ser do mesmo domínio definido pela instituição: {$instituicao->dominio_email_institucional}.",
            );
        }
    }

    function validarAnoFimCurso(int $id_curso, ?int $anoMin, ?int $anoMax): void
    {
        if (is_null($anoMin) || is_null($anoMax)) return; 
        
        $curso = $this->cursoRepository->find($id_curso);
        $intervalo = $anoMax - $anoMin;

        if ($intervalo > $curso->duracao_maxima)
        {
            throw new AnoMaximoException(
                ErrorContext::CADASTRO_USER,
                "O total de anos informado excede o máximo de {$curso->duracao_maxima} anos definido pelo curso.",
            );
        }
    }
}