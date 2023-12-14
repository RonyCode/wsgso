<?php

namespace Gso\Ws\Context\User\Infra\User\Interface;

use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignIn\OutputBoundaryUserAuthSignIn;

interface UserAuthPresentationInterface
{
    public function outPut(OutputBoundaryUserAuthSignIn $data): array;
}
