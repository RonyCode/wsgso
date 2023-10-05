<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Domains\User;

use JsonException;

final class User
{
    public function __construct(
        readonly public ?int $codUsuario = null,
        private ?UserAuth $userAuth = null,
        private ?Profile $profile = null,
        private ?Account $account = null,
        readonly public ?int $excluded = null,
    ) {
    }

    public function getCodUsuario(): ?int
    {
        return $this->codUsuario;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function getUserAuth(): ?UserAuth
    {
        return $this->userAuth;
    }

    /**
     * @throws JsonException
     */
    public function signInUserAuth(
        $email,
        $pass,
        $passExternal,
        $dateCreation,
        $excluded
    ): self {
        $this->userAuth = UserAuth::userAuthSerialize(
            $email,
            $pass,
            $passExternal,
            $dateCreation,
            $excluded
        );

        return $this;
    }

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
        $nome,
        $email,
        $cpf,
        $phone,
        $image,
        $logradouro,
        $numero,
        $cep,
        $complemento,
        $bairro,
        $cidade,
        $estado,
        $excluded,
    ): self {
        $this->account = Account::accountSerialize(
            $nome,
            $email,
            $cpf,
            $phone,
            $image,
            $excluded
        )->addAddress(
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
}
