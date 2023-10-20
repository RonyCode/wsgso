<?php

use Gso\Ws\Web\Message\Builder;

require_once __DIR__ . '/../../../../../vendor/autoload.php';


$server = [
    'host' => 'localhost',
    'port' => 5672,
    'user' => 'guest',
    'pass' => 'guest',
];

Builder::exchange('process.log', $server)->receive("yyy.log", function ($routingKey, $data) {
    error_log($routingKey." - ".json_encode($data));
});
