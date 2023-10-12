<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

try {
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel    = $connection->channel();

    $channel->queue_declare('hello', false, false, false, false);

    $msg = new AMQPMessage('Hello World teste!');
    $channel->basic_publish($msg, '', 'hello');

    echo " [x] Sent 'Hello World teste!'\n";

    $channel->close();

    $connection->close();
} catch (Exception $e) {
    echo 'Error: ', $e->getMessage(), "\n";
}
