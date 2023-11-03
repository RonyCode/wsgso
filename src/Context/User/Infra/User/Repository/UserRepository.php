<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Infra\User\Repository;

use Gso\Ws\Context\User\Domains\User\Interface\UserRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\User;
use Gso\Ws\Context\User\Infra\Connection\Interfaces\GlobalConnectionInterface;
use Gso\Ws\Context\User\Infra\User\services\PassHandleUserService;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

final class UserRepository implements UserRepositoryInterface
{
    use ResponseError;

    public function __construct(
        private readonly GlobalConnectionInterface $globalConnection,
    ) {
    }

    public function login(string $email, string $senha): User
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
            if (! (new PassHandleUserService())->verifyPassUser($senha, (string)$objUsuario->senha)) {
                throw new \RuntimeException();
            }

            return $objUsuario;
        } catch (RuntimeException) {
            //            $this->responseCatchError("Usuário ou senha não encontrados!");

            return new User();
        }
    }

    public function saveNewUsuarioAuth(User $usuario): User
    {
        try {
            if ($usuario->id) {
                return $this->updateUserAuthLogin($usuario);
            }

            return $this->insertNewUserlogin($usuario);
        } catch (\RuntimeException) {
            $this->responseCatchError('Não foi possível encontrar usuário!');
        }
    }


    public function getUsuarioAuthById(int $codUsuario): User
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

            return new User();
        }
    }

    public function getUsuarioByEmail(string $email): User
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

            return new User();
        }
    }

    private function insertNewUserlogin(User $usuario): User
    {
        try {
            $stmt = $this->globalConnection->conn()->prepare(
                'INSERT INTO USUARIO_AUTH 
                    (CODUSUARIO, CPF, NOME, EMAIL, SENHA, SENHAEXTERNA, DATACADASTRO, IMAGE, EXCLUIDO) 
                    VALUES (:codUsuario,:cpf,:nome,:email,:senha,:senhaExterna,:dataCadastro,:image,:excluido)'
            );

            $passEncripted         = (new PassHandleUserService())->encodePassUser((string)$usuario->senha);
            $passExternalEncripted = (new PassHandleUserService())->encodePassUser((string)$usuario->senhaExterna);

            $stmt->bindValue(':codUsuario', $usuario->codUsuario, \PDO::PARAM_INT);
            $stmt->bindValue(':cpf', $usuario->cpf, \PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':nome', $usuario->nome, \PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':email', $usuario->email, \PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':senha', $passEncripted);
            $stmt->bindValue(':senhaExterna', $passExternalEncripted);
            $stmt->bindValue(':dataCadastro', $usuario->dataCadastro, \PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':image', $usuario->image, \PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':excluido', $usuario->excluido, \PDO::PARAM_INT);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                return new User();
            }

            return $this->getUsuarioAuthById((int)$this->globalConnection->conn()->lastInsertId());
        } catch (RuntimeException) {
            $this->responseCatchError('Novo usuário não pôde ser salvo');
        }
    }

    private function updateUserAuthLogin(User $usuario): User
    {
        try {
            $stmt = $this->globalConnection->conn()->prepare(
                'UPDATE USUARIO_AUTH SET SENHAEXTERNA = :senhaExterna WHERE CODUSUARIO = :codUsuario'
            );

            $passEncripted = (new PassHandleUserService())->encodePassUser((string)$usuario->senhaExterna);

            $stmt->bindValue(':codUsuario', $usuario->codUsuario, \PDO::PARAM_INT);
            $stmt->bindValue(':senhaExterna', $passEncripted);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }

            return $this->getUsuarioAuthById($usuario->codUsuario);
        } catch (RuntimeException) {
            //            $this->responseCatchError("Usuário ou senha não encontrados!");

            return new User();
        }
    }

    private function newObjUsuarioAuth($data): User
    {
        try {
            if (empty($data)) {
                throw new \RuntimeException();
            }

            return User::userSerialize(
                $data['CODUSUARIO'],
                $data['CPF'],
                $data['NOME'],
                $data['EMAIL'],
                $data['SENHA'],
                $data['SENHAEXTERNA'],
                $data['DATACADASTRO'],
                $data['IMAGE'],
                $data['EXCLUIDO'],
            );
        } catch (\RuntimeException | \JsonException) {
            return new User();
        }
    }
}
