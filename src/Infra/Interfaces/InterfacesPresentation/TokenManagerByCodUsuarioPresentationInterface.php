<?php

namespace Gso\Ws\Infra\Interfaces\InterfacesPresentation;

use Gso\Ws\App\UseCases\TokenManagerByCodUsuarioCase\OutputBoundaryTokenManagerByCodUsuario;

interface TokenManagerByCodUsuarioPresentationInterface
{
    public function outPut(OutputBoundaryTokenManagerByCodUsuario $data): array;
}
