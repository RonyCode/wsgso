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
        private ?int $accountId = null,
        private ?int $addressId = null,
        private ?int $profileId = null,
        readonly public ?int $excluded = null,
    ) {
    }

    public function getUserAuthId(): ?int
    {
        return $this->userAuthId;
    }

    public function getAddressId(): ?int
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


    public function getUserAddressId(): ?int
    {
        return $this->addressId;
    }

    public function getAccount(): ?Account
    {
        return new Account();
    }

    public function getAddress(): ?Address
    {
        return new Address();
    }

    public function getProfile(): ?Profile
    {
        return new Profile();
    }

    public function getUserAuth(): UserAuth
    {
        return new UserAuth();
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
        $this->userAuthId = (new UserAuth(
            $idUserAuth,
            $email,
            $pass,
            $isUserExternal,
            $dateCriation,
            $excluded
        ))->sanitize()->id;

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
        $this->accountId = (new Account(
            $id,
            $nome,
            $email,
            $cpf,
            $phone,
            $image,
            $excluded
        ))->sanitize()->id;

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
        $this->addressId = (new Address(
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
        ))->sanitize()->id;

        return $this;
    }

    public function addProfile(
        ?int $id = null,
        ?string $role = null,
        ?string $dateGranted = null,
        ?string $dateExpires = null,
        ?int $grantedByUser = null,
        ?int $excluded = null
    ): self {
        $this->profileId = (new Profile(
            $id,
            mb_strtolower($role),
            $dateGranted,
            $dateExpires,
            $grantedByUser,
            $excluded
        ))->sanitize()->id;

        return $this;
    }

    public function saveNewUser(
        ?UserAuth $userAuth = null,
        ?Account $account = null,
        ?Address $address = null,
        ?Profile $profile = null,
    ): array {
        return [
            'user_auth' => $userAuth,
            'account'   => $account,
            'address'   => $address,
            'profile'   => $profile,
        ];
    }
}
