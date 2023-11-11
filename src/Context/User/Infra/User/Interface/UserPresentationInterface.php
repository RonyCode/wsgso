<?php

namespace Gso\Ws\Context\User\Infra\User\Interface;

use Gso\Ws\Context\User\App\UseCases\User\UserAuthSignIn\OutputBoundaryUserAuthSignIn;

interface UserPresentationInterface
{
    public function outPut(OutputBoundaryUserAuthSignIn $data): array;
}
