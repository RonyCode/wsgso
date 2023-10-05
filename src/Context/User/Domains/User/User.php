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

    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    public function getUserAuthId(): ?int
    {
        return $this->userAuthId;
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
