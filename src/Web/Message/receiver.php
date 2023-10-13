<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

try {
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel    = $connection->channel();

    $channel->queue_declare('teste', false, false, false, false);

    echo " [*] Waiting for messages. To exit press CTRL+C\n";

    $callback = static function ($msg) {
        echo ' [x] Received ', $msg->body, "\n";
    };

    $channel->basic_consume('teste', '', false, true, false, false, $callback);

    while ($channel->is_open()) {
        $channel->wait();
    }

    $channel->close();

    $connection->close();
} catch (Exception $e) {
    echo 'Error: ', $e->getMessage(), "\n";
}
