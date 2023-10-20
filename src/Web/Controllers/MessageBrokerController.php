<?php

namespace Gso\Ws\Web\Controllers;

use Gso\Ws\Web\Message\Builder;
use Gso\Ws\Web\Message\interface\RabbitMQInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MessageBrokerController
{
    public function __construct()
    {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $queueName = $args['queue_name'];
            $server = [
                'host' => 'localhost',
                'port' => 5672,
                'user' => 'guest',
                'pass' => 'guest',
            ];
            Builder::queue($queueName, $server)->receive(function ($data, $queueNameReceive) use ($server) {
                Builder::exchange('process.log', $server)->emit("exchange.start", $queueNameReceive);
                Builder::exchange('process.log', $server)->emit("exchange.finish", $queueNameReceive);
            });

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
}
