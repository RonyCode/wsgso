<?php

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use Gso\Ws\Web\Message\Builder;

$server = [
    'host' => 'localhost',
    'port' => 5672,
    'user' => 'guest',
    'pass' => 'guest',
];
$queue  = Builder::queue('email_sended', $server);

$queue->emit(["aaa" => 1]);
$queue->emit(["aaa" => 2]);
$queue->emit(["aaa" => 3]);
