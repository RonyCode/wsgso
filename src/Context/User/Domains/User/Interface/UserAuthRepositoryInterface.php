<?php

namespace Gso\Ws\Context\User\Domains\User\Interface;

use Gso\Ws\Context\User\Domains\User\UserAuth;

interface UserAuthRepositoryInterface
{
    public function signIn(string $email, string $password): UserAuth;
    public function getUsuarioByEmail(string $email): UserAuth;
    public function saveNewUsuarioAuth(UserAuth $userAuth): UserAuth;
}
