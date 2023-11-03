<?php

namespace Gso\Ws\Context\User\Infra\User\Interface;

use Gso\Ws\Context\User\App\UseCases\User\SignInUser\OutputBoundaryUserSignIn;

interface UserPresentationInterface
{
    public function outPut(OutputBoundaryUserSignIn $data): array;
}
