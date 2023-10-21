<?php

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use Gso\Ws\Web\Message\Builder;

$server = [
    'host' => 'localhost',
    'port' => 5672,
    'user' => 'guest',
    'pass' => 'guest',
];

Builder::rpc('rpc.hello', $server)->server(function ($name, $surname) use ($server) {
    Builder::exchange('process.log', $server)->emit("rpc.start", 'rpc.hello');
    echo "Hello {$name} {$surname}";
    Builder::exchange('process.log', $server)->emit("rpc.finish", 'rpc.hello');
});
