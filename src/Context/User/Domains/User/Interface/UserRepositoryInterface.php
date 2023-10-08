<?php

namespace Gso\Ws\Context\User\Domains\User\Interface;

use Gso\Ws\Context\User\Domains\User\UserAuth;

interface UserRepositoryInterface
{
    public function getUsuarioById(int $codUsuario): UserAuth;
}
