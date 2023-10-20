<?php

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use Gso\Ws\Web\Message\Builder;

$server = [
    'host' => 'localhost',
    'port' => 5672,
    'user' => 'guest',
    'pass' => 'guest',
];
Builder::exchange('process.log', $server)->receive("exchange.start", function ($routingKey, $data) {
    echo $routingKey . " - " . $data . "\n";
});
