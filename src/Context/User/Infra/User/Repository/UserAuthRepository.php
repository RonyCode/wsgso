<?php

namespace Gso\Ws\Context\User\Infra\User\Repository;

use Gso\Ws\Context\User\Domains\User\Interface\UserAuthRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Profile;
use Gso\Ws\Context\User\Domains\User\UserAuth;
use Gso\Ws\Context\User\Infra\Connection\GlobalConnection;
use Gso\Ws\Context\User\Infra\Connection\Interfaces\GlobalConnectionInterface;
use Gso\Ws\Context\User\Infra\User\services\PassHandleUserService;
use Gso\Ws\Web\Helper\ResponseError;
use PDO;
use RuntimeException;

class UserAuthRepository implements UserAuthRepositoryInterface
{
    use ResponseError;

    private readonly PDO $globalConnection;


    public function __construct()
    {
        $this->globalConnection = GlobalConnection::conn();
    }

    public function signIn(string $email, string $password): UserAuth
    {
        try {
            $stmt = $this->globalConnection->prepare(
                'SELECT * FROM user_auth WHERE email = :email'
            );
            $stmt->bindValue(':email', $email, \PDO::PARAM_STR_CHAR);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }
            $objUsuario = $this->newObjUsuarioAuth($stmt->fetch());
            if (! (new PassHandleUserService())->verifyPassUser($password, (string)$objUsuario->password)) {
                throw new \RuntimeException();
            }

            return $objUsuario;
        } catch (RuntimeException) {
            //            $this->responseCatchError("Usuário ou senha não encontrados!");

            return new UserAuth();
        }
    }

    public function getUserAuthByEmail(string $email): UserAuth
    {
        try {
            $stmt = $this->globalConnection->prepare(
                'SELECT * FROM user_auth WHERE email = :email AND excluded = 0'
            );
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }

            return $this->newObjUsuarioAuth($stmt->fetch());
        } catch (RuntimeException) {
            //            $this->responseCatchError("Usuário ou senha não encontrados!");

            return new UserAuth();
        }
    }


    public function saveNewUserAuth(UserAuth $userAuth): UserAuth
    {
        try {
            if ($userAuth->id) {
                return $this->updateUserAuthLogin($userAuth);
            }

            return $this->insertNewUserlogin($userAuth);
        } catch (\RuntimeException) {
            $this->responseCatchError('Não foi possível encontrar usuário!');
        }
    }


    private function insertNewUserlogin(UserAuth $usuario): UserAuth
    {
        try {
            $usuarioByEmail = $this->getUserAuthByEmail($usuario->email);
            if ($usuarioByEmail->id) {
                throw new \RuntimeException('Email já cadastrado no sistema!');
            }

            $stmt = $this->globalConnection->prepare(
                'INSERT INTO user_auth 
                    (email, password,is_user_external, date_criation,excluded) 
                    VALUES (:email,:password,:isUserExternal,:dataCadastro,:excluded)'
            );

            $passEncripted = (new PassHandleUserService())->encodePassUser((string)$usuario->password);

            $stmt->bindValue(':email', $usuario->email, \PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':password', $passEncripted);
            $stmt->bindValue(':isUserExternal', $usuario->isUserExternal, \PDO::PARAM_INT);
            $stmt->bindValue(':dataCadastro', $usuario->dateCriation, \PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':excluded', $usuario->excluded, \PDO::PARAM_INT);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                return new UserAuth();
            }

            return $this->getUserAuthById((int)$this->globalConnection->lastInsertId());
        } catch (RuntimeException) {
            $this->responseCatchError('Novo usuário não pôde ser salvo ou email já cadastrado');
        }
    }


    private function updateUserAuthLogin(UserAuth $usuario): UserAuth
    {
        try {
            $passEncripted = (new PassHandleUserService())->encodePassUser((string)$usuario->password);

            $stmt = $this->globalConnection->prepare(
                'UPDATE user_auth SET email = :email,
                    password = :password,
                    date_criation = :dataCadastro,
                    is_user_external = :isUserExternal,
                    excluded = :excluded WHERE id = :id'
            );

            $stmt->bindValue(':email', $usuario->email, \PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':password', $passEncripted);
            $stmt->bindValue(':dataCadastro', $usuario->dateCriation, \PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':isUserExternal', $usuario->isUserExternal, \PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':excluded', $usuario->excluded, \PDO::PARAM_INT);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }

            return $this->getUserAuthById($usuario->id);
        } catch (RuntimeException) {
            //            $this->responseCatchError("Usuário ou senha não encontrados!");

            return new UserAuth();
        }
    }

    public function getUserAuthById(int $id): UserAuth
    {
        try {
            $stmt = $this->globalConnection->prepare(
                'SELECT * FROM user_auth WHERE id = :id AND excluded = 0'
            );
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }

            return $this->newObjUsuarioAuth($stmt->fetch());
        } catch (RuntimeException) {
            //            $this->responseCatchError("Usuário ou senha não encontrados!");

            return new UserAuth();
        }
    }

    private function newObjUsuarioAuth($data): UserAuth
    {
        try {
            if (empty($data)) {
                throw new \RuntimeException();
            }

            return new UserAuth(
                $data['id'],
                $data['email'],
                $data['password'],
                $data['is_user_external'],
                $data['date_criation'],
                $data['excluded'],
            );
        } catch (\RuntimeException | \JsonException) {
            return new UserAUth();
        }
    }
}
