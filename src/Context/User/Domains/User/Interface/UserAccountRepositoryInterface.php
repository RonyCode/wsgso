<?php

namespace Gso\Ws\Context\User\Domains\User\Interface;

use Gso\Ws\Context\User\Domains\User\Account;

interface UserAccountRepositoryInterface
{
    public function saveNewUserAccount(Account $userAccount): Account;
}
