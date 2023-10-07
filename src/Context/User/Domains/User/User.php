<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Domains\User;

use JsonException;

final class User
{
    public function __construct(
        readonly public ?int $id = null,
        private ?int $userAuthId = null,
        private ?int $accountId = null,
        private ?Profile $profile = null,
        readonly public ?int $excluded = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function getAccount(): ?Account
    {
        return Account::accountSerialize();
    }

    public function getUserAuth(): UserAuth
    {
        return (new UserAuth())->getUserAuth();
    }

    /**
     * @throws JsonException
     */


    /**
     * @throws JsonException
     */
    public function addProfile(
        $role,
        $dateGranted,
        $dateExpires,
        $grantedByUser,
        $excluded
    ): self {
        $this->profile = Profile::profileSerialize(
            $role,
            $dateGranted,
            $dateExpires,
            $grantedByUser,
            $excluded
        );

        return $this;
    }

    /**
     * @throws JsonException
     */
    public function addAccount(
        ?int $id = null,
        ?int $userId = null,
        ?string $nome = null,
        ?string $email = null,
        ?string $cpf = null,
        ?string $phone = null,
        ?string $image = null,
        ?int $excluded = null,
    ): self {
        $this->accountId = Account::accountSerialize(
            $id,
            $userId,
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
        ?string $logradouro = null,
        ?string $numero = null,
        ?string $cep = null,
        ?string $complemento = null,
        ?string $bairro = null,
        ?string $cidade = null,
        ?string $estado = null,
        ?int $excluded = null
    ): self {
        (new Account())->addAddress(
            $logradouro,
            $numero,
            $cep,
            $complemento,
            $bairro,
            $cidade,
            $estado,
            $excluded
        );

        return $this;
    }

    /**
     * @throws JsonException
     */
    public function addUserLogin(
        ?int $idUserAuth = null,
        ?string $email = null,
        ?string $pass = null,
        ?string $passExternal = null,
        ?string $dateCriation = null,
        ?int $excluded = null,
    ): self {
        $this->userAuthId = UserAuth::userAuthSerialize(
            $idUserAuth,
            $email,
            $pass,
            $passExternal,
            $dateCriation,
            $excluded
        )->id;

        return $this;
    }


    /**
     * @throws JsonException
     */
    public function userSignIn(
        ?string $email = null,
        ?string $password = null,
    ): self {
        $this->userAuthId = (new UserAuth())->signIn(
            $email,
            $password
        )->id;

        return $this;
    }
}
