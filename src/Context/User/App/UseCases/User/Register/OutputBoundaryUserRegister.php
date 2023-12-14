<?php

namespace Gso\Ws\Context\User\App\UseCases\User\Register;

class OutputBoundaryUserRegister
{
    public function __construct(
        readonly public int $id,
        readonly public int $idUserAuth,
        readonly public int $idAccount,
        readonly public int $idAddress,
        readonly public int $idProfile,
        readonly public int $excluded
    ) {
    }
}
