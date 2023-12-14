<?php

namespace Gso\Ws\Context\User\Infra\User\Interface;

use Gso\Ws\Context\User\App\UseCases\User\Register\OutputBoundaryUserRegister;

interface UserPresentationInterface
{
    public function outPut(OutputBoundaryUserRegister $data): array;
}
