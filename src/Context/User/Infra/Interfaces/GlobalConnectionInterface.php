<?php

namespace Gso\Ws\Context\User\Infra\Interfaces;

use PDO;

interface GlobalConnectionInterface
{
    public function conn(): PDO;
}
