<?php

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use Gso\Ws\Web\Message\Builder;

$server = [
    'host' => '127.0.0.1',
    'port' => 5672,
    'user' => 'guest',
    'pass' => 'guest',
];
$queue  = Builder::queue('queue', $server);

$queue->emit(["aaa" => 1]);
$queue->emit(["aaa" => 2]);
$queue->emit(["aaa" => 3]);
