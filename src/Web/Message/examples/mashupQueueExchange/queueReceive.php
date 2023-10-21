<?php

use Gso\Ws\Web\Message\Builder;

require_once __DIR__ . '/../../../../../vendor/autoload.php';


$server = [
    'host' => 'localhost',
    'port' => 5672,
    'user' => 'guest',
    'pass' => 'guest',
];


$teste = function ($msg) {
    echo "<pre>";
    print_r($msg);
    echo "</pre>";
};

Builder::queue('queue', $server)->receive(function ($data, $queueName) use ($server, $teste) {
    Builder::exchange('process.log', $server)->emit("exchange.start", $queueName);
    $teste($data);
    Builder::exchange('process.log', $server)->emit("exchange.finish", $queueName);
});
