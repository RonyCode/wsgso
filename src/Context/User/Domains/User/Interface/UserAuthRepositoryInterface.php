<?php

namespace Gso\Ws\Context\User\Domains\User\Interface;

use Gso\Ws\Context\User\Domains\User\UserAuth;

interface UserAuthRepositoryInterface
{
    public function signIn(string $email, string $password): UserAuth;

    public function getUserAuthByEmail(string $email): UserAuth;

    public function getUserAuthById(int $id): UserAuth;

    public function saveNewUserAuth(UserAuth $userAuth): UserAuth;
}
