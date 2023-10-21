<?php

namespace Gso\Ws\Web\Controllers;

use Gso\Ws\Web\Message\Builder;
use Gso\Ws\Web\Message\interface\RabbitMQInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MessageBrokerController
{
    private $messagemConsumed;

    public function __construct()
    {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $queueName = $args['queue_name'];
            $server    = [
                'host' => 'localhost',
                'port' => 5672,
                'user' => 'guest',
                'pass' => 'guest',
            ];
            Builder::queue($queueName, $server)->receive(function ($data, $queueName) use ($server) {
                Builder::exchange('process.log', $server)->emit("exchange.start", $queueName);
                $this->messagemConsumed = $this->processMesage($data);
                Builder::exchange('process.log', $server)->emit("exchange.finish", $queueName);
            });

            $response->getBody()->write($this->messagemConsumed);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(202);
        } catch (\RuntimeException $e) {
            $result = [
                'status'  => 'failure',
                'code'    => 401,
                'message' => $e->getMessage(),
            ];
            $response->getBody()->write(json_encode($result, JSON_THROW_ON_ERROR | 64 | 256));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }
    }

    /**
     * @throws \JsonException
     */
    public function processMesage($message): string
    {
        return $message;
    }
}
