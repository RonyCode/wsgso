<?php

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel    = $connection->channel();

$channel->queue_declare('task_queue', false, true, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$test     = '';
$callback = function ($msg) {
//    echo ' [x] Received ', $msg->getBody(), "\n";
//    sleep(substr_count($msg->getBody(), '.'));
//    echo " [x] Done\n";
    $msg->getBody();
    $msg->ack();

    $test = $msg->getBody();
};
var_dump($callback);

$channel->basic_qos(0, 1, false);
$channel->basic_consume('queue', '', false, false, false, false, $callback);

try {
    $channel->consume();
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}

$messageConsumed = '';


$tet = function (function ($msg) use ($messageConsumed) {
    $messageConsumed = $msg;
    echo " [x] Done\n";
});




$channel->close();
$connection->close();
