<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Gso\Ws\Web\Controllers\MessageBrokerController;
use Gso\Ws\Web\Message\Builder;

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$server = [
    'host' => 'localhost',
    'port' => 5672,
    'user' => 'guest',
    'pass' => 'guest',
];


Builder::queue('queue', $server)->receive(function ($msg, $queueName) use ($server) {
    Builder::exchange('process.log', $server)->emit("exchange.start", $queueName);
    echo $msg;
    Builder::exchange('process.log', $server)->emit("exchange.finish", $queueName);
});
