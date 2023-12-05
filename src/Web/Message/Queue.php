<?php

namespace Gso\Ws\Web\Message;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Queue
{
    private string $name;
    private array $conf;
    private int $consumed = 0;
    private array $arrayMessages = [];


    public function __construct($name, $conf)
    {
        $this->name = $name;
        $this->conf = $conf;
    }


    /**
     * @throws \Exception
     */
    private function createConnection(): AMQPStreamConnection
    {
        $server = $this->conf['server'];

        return new AMQPStreamConnection($server['host'], $server['port'], $server['user'], $server['pass']);
    }

    private function declareQueue($channel): void
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
     * @throws \Exception
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

    /**
     * @throws \ErrorException
     * @throws Exception
     */
    public function receive(callable $callback)
    {
        $connection = $this->createConnection();
        $channel    = $connection->channel();

        $this->declareQueue($channel);
        $consumer = $this->conf['consumer'];
        [$queue, $messageCount, $consumerCount] = $channel->queue_declare($this->name, true);

        if ($consumer['no_ack'] === false) {
            $channel->basic_qos(null, 1, null);
        }

        if ($messageCount == 0) {
            $channel->close();
            $connection->close();
        }


        $channel->basic_consume(
            $this->name,
            '',
            $consumer['no_local'],
            $consumer['no_ack'],
            $consumer['exclusive'],
            $consumer['nowait'],
            function ($msg) use ($callback) {
                $this->arrayMessages['messages'][] = json_decode($msg->body, true, 512, JSON_THROW_ON_ERROR);
                $callback(
                    $this->arrayMessages,
                    $this->name
                );
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
                ++$this->consumed;
            }
        );

        while (count($channel->callbacks)) {
            if (
                $messageCount > 0
                && $this->consumed >= $messageCount
            ) {
                $channel->close();

                break;
            }

            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    public function teste()
    {
        $connection = $this->createConnection();
        $channel    = $connection->channel();
        [$queue, $messageCount, $consumerCount] = $channel->queue_declare($this->name, true);

        var_dump($queue);
        var_dump($consumerCount);
        var_dump($messageCount);

        return $messageCount;
    }
}
