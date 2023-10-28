<?php

declare(strict_types=1);

use Gso\Ws\Web\Message\Builder;

require_once __DIR__ . '/../../../../../vendor/autoload.php';


$queueName = 'queue';
$server    = [
    'host' => 'localhost',
    'port' => 5672,
    'user' => 'guest',
    'pass' => 'guest',
];
Builder::queue($queueName, $server)->receive(function ($data, $queueNameFunction) use ($server) {
    Builder::exchange('process.log', $server)->emit("exchange.start", $queueNameFunction);
    echo json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    Builder::exchange('process.log', $server)->emit("exchange.finish", $queueNameFunction);
});
