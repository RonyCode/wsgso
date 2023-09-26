<?php

declare(strict_types=1);

namespace Gso\Ws\Infra\Repositories\RepositoriesModel;

use Gso\Ws\App\Helper\ResponseError;
use Gso\Ws\Domains\Contracts\UsuarioAuthRepositoryInterface;
use Gso\Ws\Domains\Models\UsuarioModel;
use Gso\Ws\Domains\ValuesObjects\Cpf;
use Gso\Ws\Domains\ValuesObjects\Email;
use Gso\Ws\Domains\ValuesObjects\Senha;
use Gso\Ws\Infra\Interfaces\GlobalConnectionInterface;
use RuntimeException;

final class UsuarioAuthRepository implements UsuarioAuthRepositoryInterface
{
    use ResponseError;

    public function __construct(
        private readonly GlobalConnectionInterface $globalConnection,
    ) {
    }

    public function login(string $email, string $senha): UsuarioModel
    {
        try {
            $stmt = $this->globalConnection->conn()->prepare(
                'SELECT * FROM USUARIO_AUTH WHERE EMAIL = :email'
            );
            $stmt->bindValue(':email', $email, \PDO::PARAM_STR_CHAR);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }
            $objUsuario = $this->newObjUsuarioAuth($stmt->fetch());

            if (!password_verify($senha, (string) $objUsuario->senha)) {
                throw new \RuntimeException();
            }

            return $objUsuario;
        } catch (RuntimeException) {
            //            $this->responseCatchError("Usuário ou senha não encontrados!");

            return new UsuarioModel();
        }
    }

    public function saveNewUsuarioAuth(UsuarioModel $usuario): UsuarioModel
    {
        try {
            if ($usuario->codUsuario) {
                return $this->updateUserAuthLogin($usuario);
            }

            return $this->insertNewUserlogin($usuario);
        } catch (\RuntimeException) {
            $this->responseCatchError('Não foi possível encontrar usuário!');
        }
    }

    public function loginUsuarioExterno(string $email, string $senha): UsuarioModel
    {
        try {
            $stmt = $this->globalConnection->conn()->prepare(
                'SELECT * FROM USUARIO_AUTH WHERE EMAIL = :email'
            );
            $stmt->bindValue(':email', $email, \PDO::PARAM_STR_CHAR);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }
            $objUsuario = $this->newObjUsuarioAuth($stmt->fetch());

            if (!password_verify($senha, (string) $objUsuario->senhaExterna)) {
                throw new \RuntimeException();
            }

            return $objUsuario;
        } catch (RuntimeException) {
            return new UsuarioModel();
        }
    }

    public function getUsuarioById(int $codUsuario): UsuarioModel
    {
        try {
            $stmt = $this->globalConnection->conn()->prepare(
                'SELECT * FROM USUARIO_AUTH WHERE CODUSUARIO = :codUsuario AND EXCLUIDO = 0'
            );
            $stmt->bindValue(':codUsuario', $codUsuario, \PDO::PARAM_INT);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }

            return $this->newObjUsuarioAuth($stmt->fetch());
        } catch (RuntimeException) {
            //            $this->responseCatchError("Usuário ou senha não encontrados!");

            return new UsuarioModel();
        }
    }

    public function getUsuarioByEmail(string $email): UsuarioModel
    {
        try {
            $stmt = $this->globalConnection->conn()->prepare(
                'SELECT * FROM USUARIO_AUTH WHERE EMAIL = :email AND EXCLUIDO = 0'
            );
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }

            return $this->newObjUsuarioAuth($stmt->fetch());
        } catch (RuntimeException) {
            //            $this->responseCatchError("Usuário ou senha não encontrados!");

            return new UsuarioModel();
        }
    }

    private function insertNewUserlogin(UsuarioModel $usuario): UsuarioModel
    {
        try {
            $stmt = $this->globalConnection->conn()->prepare(
                'INSERT INTO USUARIO_AUTH (CODUSUARIO, CPF, NOME, EMAIL, SENHA, SENHAEXTERNA, DATACADASTRO, IMAGE, EXCLUIDO) 
                    VALUES (:codUsuario,:cpf,:nome,:email,:senha,:senhaExterna,:dataCadastro,:image,:excluido)'
            );
            $stmt->bindValue(':codUsuario', $usuario->codUsuario, \PDO::PARAM_INT);
            $stmt->bindValue(':cpf', $usuario->cpf, \PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':nome', $usuario->nome, \PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':email', $usuario->email, \PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':senha', password_hash((string) $usuario->senha, PASSWORD_ARGON2I));
            $stmt->bindValue(':senhaExterna', password_hash($usuario->senhaExterna, PASSWORD_ARGON2I));
            $stmt->bindValue(':dataCadastro', $usuario->dataCadastro, \PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':image', $usuario->image, \PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':excluido', $usuario->excluido, \PDO::PARAM_INT);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                return new UsuarioModel();
            }

            return $this->getUsuarioById((int) $this->globalConnection->conn()->lastInsertId());
        } catch (RuntimeException) {
            $this->responseCatchError('Novo usuário não pôde ser salvo');
        }
    }

    private function updateUserAuthLogin(UsuarioModel $usuario): UsuarioModel
    {
        try {
            $stmt = $this->globalConnection->conn()->prepare(
                'UPDATE USUARIO_AUTH SET SENHAEXTERNA = :senhaExterna WHERE CODUSUARIO = :codUsuario'
            );
            $stmt->bindValue(':codUsuario', $usuario->codUsuario, \PDO::PARAM_INT);
            $stmt->bindValue(':senhaExterna', password_hash($usuario->senhaExterna, PASSWORD_ARGON2I));
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }

            return $this->getUsuarioById($usuario->codUsuario);
        } catch (RuntimeException) {
            //            $this->responseCatchError("Usuário ou senha não encontrados!");

            return new UsuarioModel();
        }
    }

    private function newObjUsuarioAuth($data): UsuarioModel
    {
        try {
            if (!$data) {
                throw new \RuntimeException();
            }

            return new UsuarioModel(
                $data['CODUSUARIO'],
                new Cpf($data['CPF']) ?? null,
                $data['NOME'],
                new Email($data['EMAIL']),
                new Senha($data['SENHA']),
                $data['SENHAEXTERNA'],
                $data['DATACADASTRO'],
                $data['IMAGE'],
                $data['EXCLUIDO'],
            );
        } catch (\RuntimeException|\JsonException) {
            return new UsuarioModel();
        }
    }
}
