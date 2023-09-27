<?php

namespace Gso\Ws\Infra\User\Interface;



use Gso\Ws\App\UseCases\User\SignInUser\OutputBoundaryUserSignIn;

interface UserPresentationInterface
{
    public function outPut(OutputBoundaryUserSignIn $data): array;
}
