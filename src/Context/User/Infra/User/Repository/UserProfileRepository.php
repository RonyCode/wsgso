<?php

namespace Gso\Ws\Context\User\Infra\User\Repository;

use AllowDynamicProperties;
use Gso\Ws\Context\User\Domains\User\Account;
use Gso\Ws\Context\User\Domains\User\Interface\UserProfileRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Profile;
use Gso\Ws\Context\User\Infra\Connection\GlobalConnection;
use Gso\Ws\Web\Helper\ResponseError;
use PDOStatement;
use RuntimeException;

#[AllowDynamicProperties] final class UserProfileRepository implements UserProfileRepositoryInterface
{
    use ResponseError;

    public function __construct()
    {
        $this->globalConnection = GlobalConnection::conn();
    }


    public function saveNewUserProfile(Profile $profile): Profile
    {
        try {
            if ($profile->id) {
                return $this->updateProfile($profile);
            }

            return $this->insertProfile($profile);
        } catch (\RuntimeException) {
            $this->responseCatchError('Não foi possível encontrar usuário!');
        }
    }


    private function insertProfile(Profile $profile): Profile
    {
        try {
            $stmt = $this->globalConnection->prepare(
                'INSERT INTO user_profile 
                        ( role, date_granted, date_expires, granted_by_iduser, excluded) 
                    VALUES ( :role, :dateGranted, :dateExpires, :idUserGranted, :excluded)'
            );
            $this->extracted($stmt, $profile);

            return $this->getAccountById((int)$this->globalConnection->lastInsertId());
        } catch (\RuntimeException | \JsonException $e) {
            $this->responseCatchError($e->getMessage());
        }
    }

    private function newObjUserProfile($data): Profile
    {
        try {
            if (empty($data)) {
                throw new \RuntimeException();
            }

            return new  Profile(
                $data['id'] ?? null,
                mb_strtolower($data['role']),
                $data['date_granted'] ?? null,
                $data['date_expires'] ?? null,
                $data['granted_by_iduser'] ?? null,
                $data['excluded'] ?? null,
            );
        } catch (\RuntimeException | \JsonException) {
            return new Profile();
        }
    }

    public function getAccountById(int $id): Profile
    {
        try {
            $stmt = $this->globalConnection->prepare(
                'SELECT * FROM user_profile WHERE id = :id AND excluded = 0'
            );
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }

            return $this->newObjUserProfile($stmt->fetch());
        } catch (RuntimeException) {
            return new Profile();
        }
    }

    private function updateProfile(Profile $profile): Profile
    {
        try {
            $stmt = $this->globalConnection->prepare(
                'UPDATE user_profile SET 
                    role = :role,
                    date_granted = :dateGranted,
                    date_expires = :dateExpires,
                    granted_by_iduser = :idUserGranted,
                    excluded = :excluded
                    WHERE id = :id  '
            );

            $stmt->bindValue(':id', $profile->id);
            $this->extracted($stmt, $profile);

            return $this->getAccountById($profile->id);
        } catch (RuntimeException) {
            return new Profile();
        }
    }

    /**
     * @param false|PDOStatement $stmt
     * @param Profile $profile
     *
     * @return void
     */
    private function extracted(false|PDOStatement $stmt, Profile $profile): void
    {
        $stmt->bindValue(':role', $profile->role);
        $stmt->bindValue(':dateGranted', $profile->dateGranted);
        $stmt->bindValue(':dateExpires', $profile->dateExpires);
        $stmt->bindValue(':idUserGranted', $profile->grantedByIdUser);
        $stmt->bindValue(':excluded', $profile->excluded);
        $stmt->execute();
        if (0 === $stmt->rowCount()) {
            throw new \RuntimeException();
        }
    }
}
