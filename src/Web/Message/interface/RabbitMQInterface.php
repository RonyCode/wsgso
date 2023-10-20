<?php

namespace Gso\Ws\Web\Message\interface;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;

interface RabbitMQInterface
{
    public function connectAMPQ(): AbstractConnection;

    public function selectQueueAMPQ(?string $queueName = null): AMQPChannel;

    public function createQueueAMPQ(
        ?string $queueName = null,
        ?bool $passive = false,
        ?bool $durable = false,
        ?bool $exclusive = false,
        ?bool $autoDelete = false
    ): AMQPChannel;
}
