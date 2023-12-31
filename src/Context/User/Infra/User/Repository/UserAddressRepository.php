<?php

namespace Gso\Ws\Context\User\Infra\User\Repository;

use AllowDynamicProperties;
use Gso\Ws\Context\User\Domains\User\Account;
use Gso\Ws\Context\User\Domains\User\Address;
use Gso\Ws\Context\User\Domains\User\Interface\UserAddressRepositoryInterface;
use Gso\Ws\Context\User\Infra\Connection\GlobalConnection;
use Gso\Ws\Web\Helper\ResponseError;
use PDOStatement;
use RuntimeException;

#[AllowDynamicProperties] class UserAddressRepository implements UserAddressRepositoryInterface
{
    use ResponseError;

    public function __construct()
    {
        $this->globalConnection = GlobalConnection::conn();
    }


    #[\Override] public function getAllCities(): array
    {
        try {
            $stmt = $this->globalConnection->query(
                'SELECT * FROM cidade'
            );
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }


            $cities = $stmt->fetchAll();

            foreach ($cities as $city) {
                $citiesObj[] = $this->newObjAddress($city);
            }

            return $citiesObj;
        } catch (RuntimeException $e) {
            $this->responseCatchError($e->getMessage());
        }
    }

    /**
     * @return Address[]
     * @throws \JsonException
     */
    #[\Override] public function getAllStates(): array
    {
        try {
            $stmt = $this->globalConnection->query(
                'SELECT * FROM estado'
            );
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }

            $states = $stmt->fetchAll();

            foreach ($states as $state) {
                $stateObj[] = $this->newObjAddress($state);
            }

            return $stateObj;
        } catch (RuntimeException $e) {
            $this->responseCatchError($e->getMessage());
        }
    }


    private function newObjAddress($data): Address
    {
        try {
            if (empty($data)) {
                throw new \RuntimeException();
            }

            $address = new Address(
                $data['id'] ?? null,
                $data['address'] ?? null,
                $data['number'] ?? null,
                $data['zip_code'] ?? null,
                $data['complement'] ?? null,
                $data['district'] ?? null,
                $data['city'] ?? null,
                $data['state'] ?? null,
                $data['short_name'] ?? null,
                0
            );

            return $address;
        } catch (\RuntimeException | \JsonException) {
            return new Address();
        }
    }

    #[\Override] public function getAllCitiesByState(string $state): array
    {
        try {
            $stmt = $this->globalConnection->prepare(
                'SELECT * FROM cidade WHERE state = :state',
            );
            $stmt->bindValue(':state', $state);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException('Erro na busca de cidades por Estado');
            }


            $cities = $stmt->fetchAll();

            foreach ($cities as $city) {
                $citiesObj[] = $this->newObjAddress($city);
            }

            return $citiesObj;
        } catch (RuntimeException $e) {
            $this->responseCatchError($e->getMessage());
        }
    }

    #[\Override] public function saveNewAddressUser(Address $address): Address
    {
        try {
            if ($address->id) {
                return $this->updateAddress($address);
            }

            return $this->insertAddress($address);
        } catch (\RuntimeException) {
            $this->responseCatchError('Não foi possível encontrar usuário!');
        }
    }

    private function insertAddress(Address $address): Address
    {
        try {
            $stmt = $this->globalConnection->prepare(
                "INSERT INTO user_address 
                ( address,number, zip_code, complement, district, city, state,short_name,excluded) 
                VALUES (:address, :number, :zip_code, :complement, :district, :city, :state, :short_name, :excluded)"
            );
            $this->extracted($stmt, $address);


            return $this->getAddressById((int)$this->globalConnection->lastInsertId());
        } catch (\RuntimeException | \JsonException $e) {
            $this->responseCatchError($e->getMessage());
        }
    }


    private function updateAddress(Address $address)
    {
        try {
            $stmt = $this->globalConnection->prepare(
                "UPDATE user_address 
                    SET address = :address,number =:number,
                        zip_code = :zipCode,complement = :complement,
                        district= :district,city = :city,
                        state = :state,short_name = :short_name,
                        excluded = :excluded WHERE id = :id"
            );
            $stmt->bindValue(':id', $address->id);
            $this->extracted($stmt, $address);


            return $this->getAddressById((int)$this->globalConnection->lastInsertId());
        } catch (\RuntimeException | \JsonException $e) {
            $this->responseCatchError($e->getMessage());
        }
    }

    /**
     * @param false|PDOStatement $stmt
     * @param Address $address
     *
     * @return void
     */
    private function extracted(false|PDOStatement $stmt, Address $address): void
    {
        $stmt->bindValue(':address', $address->address);
        $stmt->bindValue(':number', $address->number);
        $stmt->bindValue(':zip_code', $address->zipCode);
        $stmt->bindValue(':complement', $address->complement);
        $stmt->bindValue(':district', $address->district);
        $stmt->bindValue(':city', $address->city);
        $stmt->bindValue(':state', $address->state);
        $stmt->bindValue(':short_name', $address->shortName);
        $stmt->bindValue(':excluded', $address->excluded);
        $stmt->execute();
        if (0 === $stmt->rowCount()) {
            throw new \RuntimeException();
        }
    }

    public function getAddressById(int $id): Address
    {
        try {
            $stmt = $this->globalConnection->prepare(
                'SELECT * FROM user_address WHERE id = :id AND excluded = 0'
            );
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            if (0 === $stmt->rowCount()) {
                throw new \RuntimeException();
            }

            return $this->newObjAddress($stmt->fetch());
        } catch (RuntimeException) {
            return new Address();
        }
    }
}
