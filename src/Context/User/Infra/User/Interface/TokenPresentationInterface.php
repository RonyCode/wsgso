<?php

namespace Gso\Ws\Context\User\Infra\User\Interface;

use Gso\Ws\Context\User\App\UseCases\Token\GetTokenByCodUser\OutputBoundaryRefreshTokenCase;

interface TokenPresentationInterface
{
    public function outPut(OutputBoundaryRefreshTokenCase $data): array;
}
