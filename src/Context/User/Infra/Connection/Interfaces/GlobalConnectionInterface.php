<?php

namespace Gso\Ws\Context\User\Infra\Connection\Interfaces;

use PDO;

interface GlobalConnectionInterface
{
    public function conn(): PDO;
}
