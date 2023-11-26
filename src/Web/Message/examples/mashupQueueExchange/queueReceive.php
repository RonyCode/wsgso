<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Gso\Ws\Web\Controllers\MessageBrokerController;
use Gso\Ws\Web\Message\Builder;

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$server = [
    'host' => '127.0.0.1',
    'port' => 5672,
    'user' => 'guest',
    'pass' => 'guest',
];

Builder::queue('queue', $server)->receive(function ($msg) {
    var_dump($msg);
});
