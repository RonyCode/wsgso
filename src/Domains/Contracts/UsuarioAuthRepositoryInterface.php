<?php

namespace Gso\Ws\Domains\Contracts;

use Gso\Ws\Domains\Models\UsuarioModel;

interface UsuarioAuthRepositoryInterface
{
    public function login(string $email, string $senha): UsuarioModel;

    public function getUsuarioById(int $codUsuario): UsuarioModel;
}
