<?php

namespace Gso\Ws\Domains\User\Interface;

use Gso\Ws\Domains\User\User;

interface UserRepositoryInterface
{
    public function login(string $email, string $senha): User;

    public function getUsuarioById(int $codUsuario): User;
}
