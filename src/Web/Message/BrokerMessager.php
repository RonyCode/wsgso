<?php

namespace Gso\Ws\Web\Message;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class BrokerMessager
{
    private $channel;
    private $connection;

    /**
     * @throws Exception
     */
    public function __construct(
        public readonly ?bool $passive = null,
        public readonly ?bool $durable = null,
        public readonly ?bool $exclusive = null,
        public readonly ?bool $autoDelete = null,
    ) {
        $this->connection = new AMQPStreamConnection(
            getenv('BROKER_HOST'),
            getenv('BROKER_PORT'),
            getenv('BROKER_NAME'),
            getenv('BROKER_PASS')
        );
        $this->channel    = $this->connection->channel();
    }

    public function sentMessageBroker(
        ?string $queueName = null,
        ?string $exchange = null,
        ?string $message = null
    ): void {
        try {
            $this->channel->queue_declare(
                $queueName ?? 'Default',
                $this->passive ?? false,
                $this->durable ?? false,
                $this->exclusive ?? false,
                $this->autoDelete ?? false
            );

            $this->channel->basic_publish(
                new AMQPMessage($message ?: 'Hello World!'),
                $exchange ?: '',
                $queueName
            );

            echo " [x] Sent " . $message . "\n";

            $this->channel->close();
            $this->connection->close();
        } catch (Exception $e) {
            echo 'Error: ', $e->getMessage(), "\n";
        }
    }

    public function consumeMessageBroker(
        ?string $queueName = null,
        ?string $consumerTag = null,
        ?bool $noLocal = null,
        ?bool $noAck = null,
        ?bool $exclusive = null,
        ?bool $noWait = null
    ): void {
        try {
            $this->channel->queue_declare(
                $queueName ?? 'Default',
                $this->passive ?? false,
                $this->durable ?? false,
                $this->exclusive ?? false,
                $this->autoDelete ?? false
            );

            echo " [*] Waiting for messages. To exit press CTRL+C\n";

            $callback = function ($msg) {
                echo ' [x] Received ', $msg->body, "\n";
            };

            $this->channel->basic_consume(
                $queueName,
                $consumerTag ?? '',
                $noLocal ?? false,
                $noAck ?? true,
                $exclusive ?? false,
                $noWait ?? false,
                $callback
            );
            while ($this->channel->is_open()) {
                echo 'test';
//                $this->channel->wait();
            }
            $this->channel->close();
            $this->connection->close();
        } catch (Exception $e) {
            echo 'Error: ', $e->getMessage(), "\n";
        }
    }
}
