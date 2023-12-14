<?php

namespace Gso\Ws\Context\User\Infra\User\Repository;

use AllowDynamicProperties;
use Gso\Ws\Context\User\Domains\User\Account;
use Gso\Ws\Context\User\Domains\User\Interface\UserAccountRepositoryInterface;
use Gso\Ws\Context\User\Infra\Connection\GlobalConnection;
use Gso\Ws\Web\Helper\ResponseError;
use PDOStatement;
use RuntimeException;

#[AllowDynamicProperties] final class UserAccountRepository implements UserAccountRepositoryInterface
{
    use ResponseError;

    public function __construct()
    {
        $this->globalConnection = GlobalConnection::conn();
    }


    #[\Override] public function saveNewUserAccount(Account $userAccount): Account
    {
        try {
            if ($userAccount->id) {
                return $this->updateAccount($userAccount);
            }

            return $this->insertAccount($userAccount);
        } catch (\RuntimeException) {
            $this->responseCatchError('Não foi possível encontrar usuário!');
        }
    }


    private function insertAccount(Account $account): Account
    {
        try {
            $stmt = $this->globalConnection->prepare(
                'INSERT INTO user_account 
                        ( name, email, cpf, phone, image, excluded) 
                    VALUES ( :name, :email, :cpf, :phone, :image, :excluded)'
            );
            $this->extracted($stmt, $account);

            return $this->getAccountById((int)$this->globalConnection->lastInsertId());
        } catch (\RuntimeException | \JsonException $e) {
            $this->responseCatchError($e->getMessage());
        }
    }

    private function newObjUserAccount($data): Account
    {
        try {
            if (empty($data)) {
                throw new \RuntimeException();
            }

            return new Account(
                $data['id'],
                $data['name'],
                $data['email'],
                $data['cpf'],
                $data['phone'],
                $data['image'],
                $data['excluded']
            );
        } catch (\RuntimeException | \JsonException) {
            return new Account();
        }
    }

    public function getAccountById(int $id): Account
    {
        try {
            $stmt = $this->globalConnection->prepare(
                'SELECT * FROM user_account WHERE id = :id AND excluded = 0'
            );
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }

            return $this->newObjUserAccount($stmt->fetch());
        } catch (RuntimeException) {
            return new Account();
        }
    }

    private function updateAccount(Account $account): Account
    {
        try {
            $stmt = $this->globalConnection->prepare(
                'UPDATE user_account SET 
                    name = :name,
                    email = :email,
                    cpf = :cpf,
                    phone = :phone,
                    image = :image,
                    excluded = :excluded
                    WHERE id = :id  '
            );

            $stmt->bindValue(':id', $account->id);
            $this->extracted($stmt, $account);

            return $this->getAccountById($account->id);
        } catch (RuntimeException) {
            return new Account();
        }
    }

    /**
     * @param false|PDOStatement $stmt
     * @param Account $account
     *
     * @return void
     */
    private function extracted(false|PDOStatement $stmt, Account $account): void
    {
        $stmt->bindValue(':name', $account->name);
        $stmt->bindValue(':email', $account->email);
        $stmt->bindValue(':cpf', $account->cpf);
        $stmt->bindValue(':phone', $account->phone);
        $stmt->bindValue(':image', $account->image);
        $stmt->bindValue(':excluded', $account->excluded);
        $stmt->execute();
        if (0 === $stmt->rowCount()) {
            throw new \RuntimeException();
        }
    }
}
