<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Domains\User;

use AllowDynamicProperties;
use JsonException;

#[AllowDynamicProperties] final class User
{
    public function __construct(
        readonly public ?int $id = null,
        private ?int $userAuthId = null,
        private ?int $addressId = null,
        private ?int $accountId = null,
        private ?int $profileId = null,
        readonly public ?int $excluded = null,
    ) {
    }

    public function getUserAuthId(): ?int
    {
        return $this->userAuthId;
    }

    public function getUserAddressId(): ?int
    {
        return $this->addressId;
    }

    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    public function getProfileId(): ?int
    {
        return $this->profileId;
    }

    /**
     * @throws JsonException
     */
    public function getAccount(): ?Account
    {
        return Account::accountSerialize();
    }

    /**
     * @throws JsonException
     */
    public function getUserAuth(): UserAuth
    {
        return UserAuth::userAuthSerialize();
    }

    /**
     * @throws JsonException
     */
    public function addProfile(
        ?int $id = null,
        ?string $role = null,
        ?string $dateGranted = null,
        ?string $dateExpires = null,
        ?int $grantedByUser = null,
        ?int $excluded = null
    ): self {
        $this->profileId = Profile::profileSerialize(
            $id,
            mb_strtolower($role),
            $dateGranted,
            $dateExpires,
            $grantedByUser,
            $excluded
        )->id;

        return $this;
    }

    /**
     * @throws JsonException
     */
    public function addAccount(
        ?int $id = null,
        ?string $nome = null,
        ?string $email = null,
        ?string $cpf = null,
        ?string $phone = null,
        ?string $image = null,
        ?int $excluded = null,
    ): self {
        $this->accountId = Account::accountSerialize(
            $id,
            $nome,
            $email,
            $cpf,
            $phone,
            $image,
            $excluded
        )->id;


        return $this;
    }

    /**
     * @throws JsonException
     */
    public function addAddress(
        ?int $id = null,
        ?string $logradouro = null,
        ?string $numero = null,
        ?string $cep = null,
        ?string $complemento = null,
        ?string $bairro = null,
        ?string $cidade = null,
        ?string $estado = null,
        ?string $shortName = null,
        ?int $excluded = null
    ): self {
        $this->addressId = Address::addressSerialize(
            $id,
            $logradouro,
            $numero,
            $cep,
            $complemento,
            $bairro,
            $cidade,
            $estado,
            $shortName,
            $excluded
        )->id;

        return $this;
    }

    /**
     * @throws JsonException
     */
    public function addUserAuth(
        ?int $idUserAuth = null,
        ?string $email = null,
        ?string $pass = null,
        ?int $isUserExternal = null,
        ?string $dateCriation = null,
        ?int $excluded = null,
    ): self {
        $this->userAuthId = UserAuth::userAuthSerialize(
            $idUserAuth,
            $email,
            $pass,
            $isUserExternal,
            $dateCriation,
            $excluded
        )->id;

        return $this;
    }
}
