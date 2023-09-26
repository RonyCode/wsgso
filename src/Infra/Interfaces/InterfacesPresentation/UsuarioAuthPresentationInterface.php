<?php

namespace Gso\Ws\Infra\Interfaces\InterfacesPresentation;

use Gso\Ws\App\UseCases\UsuarioAuthCase\OutputBoundaryUsuarioAuth;

interface UsuarioAuthPresentationInterface
{
    public function outPut(OutputBoundaryUsuarioAuth $data): array;
}
