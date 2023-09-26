<?php

namespace Gso\Ws\Domains\Contracts;

use Gso\App\Domains\Models\TokenManagerModel;

interface TokenManagerRepositoryInterface
{
    public function selectTokenByCodUsuario(int $codusuario): TokenManagerModel;

    public function saveTokenUsuario(TokenManagerModel $tokenManagerModel): TokenManagerModel;
}
