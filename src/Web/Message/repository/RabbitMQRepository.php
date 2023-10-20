<?php

namespace Gso\Ws\Web\Message\repository;

use Exception;
use Gso\Ws\Web\Message\interface\RabbitMQInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQRepository implements RabbitMQInterface
{
    /**
     * @throws Exception
     */
    public function connectAMPQ(): AbstractConnection
    {
        return new AMQPStreamConnection(
            $_ENV['BROKER_HOST'],
            $_ENV['BROKER_PORT'],
            $_ENV['BROKER_NAME'],
            $_ENV['BROKER_PASS']
        );
    }

    /**
     * @param string|null $queueName
     *
     * @return AMQPChannel
     * @throws Exception
     */
    public function selectQueueAMPQ(?string $queueName = null): AMQPChannel
    {
        $channel = $this->connectAMPQ()->channel();

        $channel->queue_declare($queueName ?? 'Default');

        return $channel;
    }

    /**
     * @param string|null $queueName
     * @param bool|null $passive
     * @param bool|null $durable
     * @param bool|null $exclusive
     * @param bool|null $autoDelete
     *
     * @return AMQPChannel
     * @throws Exception
     */
    public function createQueueAMPQ(
        ?string $queueName = null,
        ?bool $passive = false,
        ?bool $durable = false,
        ?bool $exclusive = false,
        ?bool $autoDelete = false
    ): AMQPChannel {
        $chan = $this->connectAMPQ()->channel();

        $chan->queue_declare(
            $queueName ?? 'Default',
            $passive,
            $durable,
            $exclusive,
            $autoDelete
        );

        return $chan;
    }
}
