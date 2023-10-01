<?php

namespace Gso\Ws\Context\User\Domains\User\Interface;

use Gso\Ws\Context\User\Domains\User\User;

interface UserRepositoryInterface
{
    public function login(string $email, string $senha): User;

    public function getUsuarioById(int $codUsuario): User;
}
