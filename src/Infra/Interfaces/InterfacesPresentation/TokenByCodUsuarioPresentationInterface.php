<?php

namespace Gso\Ws\Infra\Interfaces\InterfacesPresentation;


use Gso\Ws\App\UseCases\TokenUserCase\GetTokenByCodUser\OutputBoundaryTokenByCodUsuario;

interface TokenByCodUsuarioPresentationInterface
{
    public function outPut(OutputBoundaryTokenByCodUsuario $data): array;
}
