<?php

namespace Gso\Ws\Context\User\Domains\User\Interface;

use Gso\Ws\Context\User\Domains\User\User;

interface UserRepositoryInterface
{
    public function saveNewUser(User $user): User;
}
