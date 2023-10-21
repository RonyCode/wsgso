<?php

namespace Gso\Ws\Web\Message;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Queue
{
    private $name;
    private $conf;
    private string $messageConsumed;

    public function __construct($name, $conf)
    {
        $this->name = $name;
        $this->conf = $conf;
    }

    private function createConnection(): AMQPStreamConnection
    {
        $server = $this->conf['server'];

        return new AMQPStreamConnection($server['host'], $server['port'], $server['user'], $server['pass']);
    }

    private function declareQueue($channel)
    {
        $conf = $this->conf['queue'];
        $channel->queue_declare(
            $this->name,
            $conf['passive'],
            $conf['durable'],
            $conf['exclusive'],
            $conf['auto_delete'],
            $conf['nowait']
        );
    }

    /**
     * @throws \JsonException
     */
    public function emit($data = null): void
    {
        $connection = $this->createConnection();
        $channel    = $connection->channel();
        $this->declareQueue($channel);

        $msg = new AMQPMessage(
            json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ['delivery_mode' => 2] # make message persistent
        );

        $channel->basic_publish($msg, '', $this->name);

        $channel->close();
        $connection->close();
    }

    public function receive(callable $callback): string
    {
        $connection = $this->createConnection();
        $channel    = $connection->channel();

        $this->declareQueue($channel);
        $consumer = $this->conf['consumer'];

        if ($consumer['no_ack'] === false) {
            $channel->basic_qos(0, 1, 1);
        }
        echo '[' . date('d/m/Y H:i:s') . "] Queue '{$this->name}' initialized \n";

        $channel->basic_consume(
            $this->name,
            '',
            $consumer['no_local'],
            $consumer['no_ack'],
            $consumer['exclusive'],
            $consumer['nowait'],
            function ($msg) use ($callback) {
                $callback($msg->body, $this->name);
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
                echo "\n[" . date('d/m/Y H:i:s') . "]  {$this->name}::" . $msg->body, "\n";
            }
        );


        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();

        return $this->messageConsumed;
    }
}
