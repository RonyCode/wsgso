<?php

require_once __DIR__ . '/../../../../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel    = $connection->channel();

echo " [*] Waiting for messages. To exit press CTRL+C\n";


$callback = function ($msg, $queueName) {
    var_dump($msg->body);
    var_dump($queueName);
};

$channel->basic_consume(
    'email',
    '',
    false,
    true,
    false,
    false,
    function ($msg) use ($callback) {
        $nome = '123123asllkdkl';
        $callback($msg, $nome);
        // tell rabbitmq that message is completed
        $channel = $msg->delivery_info['channel'];
        $channel->basic_ack($msg->delivery_info['delivery_tag']);
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        echo "\n[" . date('d/m/Y H:i:s') . "]  $nome::" . $msg->body, "\n";
    }
);


try {
    $channel->consume();
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}

$channel->close();
$connection->close();
