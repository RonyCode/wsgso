<?php

namespace Gso\Ws\Infra\Interfaces;

use PDO;

interface GlobalConnectionInterface
{
    public function conn(): PDO;
}
