<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Infra\User\Repository;

use Gso\Ws\Context\User\Domains\User\Interface\UserRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\User;
use Gso\Ws\Context\User\Infra\Connection\GlobalConnection;
use Gso\Ws\Context\User\Infra\Connection\Interfaces\GlobalConnectionInterface;
use Gso\Ws\Context\User\Infra\User\services\PassHandleUserService;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

final class UserRepository implements UserRepositoryInterface
{
    use ResponseError;

    private \PDO $globalConnection;

    public function __construct()
    {
        $this->globalConnection = GlobalConnection::conn();
    }


    public function saveNewUser(User $user): User
    {
        try {
            if ($user->id) {
                return $this->updateNewUser($user);
            }

            return $this->insertNewUser($user);
        } catch (\RuntimeException) {
            $this->responseCatchError('Não foi possível encontrar usuário!');
        }
    }


    public function getUserById(int $id): User
    {
        try {
            $stmt = $this->globalConnection->prepare(
                'SELECT * FROM user WHERE id = :codUsuario AND excluded = 0'
            );
            $stmt->bindValue(':codUsuario', $id, \PDO::PARAM_INT);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }

            return $this->newObjUser($stmt->fetch());
        } catch (RuntimeException) {
            return new User();
        }
    }


    private function insertNewUser(User $user): User
    {
        try {
            $stmt = $this->globalConnection->prepare(
                'INSERT INTO user 
                    (id_user_auth, id_account, id_address,id_profile,excluded) 
                    VALUES (:idUserAuth,:idAccount,:idAddress,:idProfile,:excluded)'
            );

            $stmt->bindValue(':idUserAuth', $user->getUserAuthId(), \PDO::PARAM_INT);
            $stmt->bindValue(':idAccount', $user->getAccountId(), \PDO::PARAM_INT);
            $stmt->bindValue(':idAddress', $user->getAddressId(), \PDO::PARAM_INT);
            $stmt->bindValue(':idProfile', $user->getProfileId(), \PDO::PARAM_INT);
            $stmt->bindValue(':excluded', $user->excluded);

            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                return new User();
            }

            return $this->getUserById((int)$this->globalConnection->lastInsertId());
        } catch (RuntimeException) {
            $this->responseCatchError('Novo usuário não pôde ser salvo');
        }
    }

    private function updateNewUser(User $user): User
    {
        try {
            $stmt = $this->globalConnection->prepare(
                'UPDATE user SET 
                id_user_auth = :idUserAuth, id_account = :idAccount,
                id_address = :idAddress, id_profile = :idProfile,
                excluded = :excluded WHERE id = :id'
            );


            $stmt->bindValue(':id', $user->id, \PDO::PARAM_INT);
            $stmt->bindValue(':idUserAuth', $user->getUserAuth(), \PDO::PARAM_INT);
            $stmt->bindValue(':idAccount', $user->getAccount(), \PDO::PARAM_INT);
            $stmt->bindValue(':idAddress', $user->getAddress(), \PDO::PARAM_INT);
            $stmt->bindValue(':idProfile', $user->getProfile(), \PDO::PARAM_INT);
            $stmt->bindValue(':excluded', $user->excluded, \PDO::PARAM_INT);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }

            return $this->getUserById($user->id);
        } catch (RuntimeException) {
            //            $this->responseCatchError("Usuário ou senha não encontrados!");

            return new User();
        }
    }

    private function newObjUser($data): User
    {
        try {
            if (empty($data)) {
                throw new \RuntimeException();
            }

            return new User(
                $data['id'],
                $data['id_user_auth'],
                $data['id_account'],
                $data['id_address'],
                $data['id_profile'],
                (int)$data['excluded']
            );
        } catch (\RuntimeException | \JsonException) {
            return new User();
        }
    }
}
