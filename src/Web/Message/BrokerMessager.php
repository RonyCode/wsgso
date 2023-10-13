<?php

namespace Gso\Ws\Web\Message;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class BrokerMessager
{
    private ?AMQPChannel $channel;
    private ?AMQPStreamConnection $connection;


    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            getenv('BROKER_HOST'),
            getenv('BROKER_PORT'),
            getenv('BROKER_NAME'),
            getenv('BROKER_PASS')
        );

        $this->channel = $this->connection->channel();
    }

    public function sentMessageBroker(
        ?string $queueName = null,
        ?string $exchange = null,
        ?string $message = null
    ): void {
        try {
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

    /**
     * @throws Exception
     */
    public function addQueue(
        string $nameQueue = null,
        bool $passive = false,
        bool $durable = false,
        bool $exclusive = false,
        bool $autoDelete = false,
    ): self {
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare(
            $nameQueue ?? 'Default',
            $passive,
            $durable,
            $exclusive,
            $autoDelete
        );

        return $this;
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
            echo " [*] Waiting for messages. To exit press CTRL+C\n";
            $this->channel->queue_declare('teste', false, false, false, false);
            $this->channel->basic_consume(
                $queueName,
                $consumerTag ?? '',
                $noLocal ?? false,
                $noAck ?? false,
                $exclusive ?? false,
                $noWait ?? false,
                function ($message) {
                    echo ' [x] Received ', json_decode($message->body, true);
                    $channel = $message->delivery_info['channel'];
                    $channel->basic_ack($message->delivery_info['delivery_tag']);
                }
            );
            while (count($this->channel->callbacks)) {
                $this->channel->wait();
            }
            $this->channel->close();
            $this->connection->close();
        } catch (Exception $e) {
            echo 'Error: ', $e->getMessage(), "\n";
        }
    }

    /**
     * @throws Exception
     */
    public function closeConnection(): void
    {
        $this->channel->close();
        $this->connection->close();
    }
}
