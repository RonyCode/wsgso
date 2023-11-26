<?php

namespace Gso\Ws\Web\Controllers;

use Exception;
use Gso\Ws\Web\Message\Builder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MessageBrokerController
{
    private array $message = [];


    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $queueName = $args['queue_name'];
            $server    = [
                'host'  => 'localhost',
                'port'  => 5672,
                'user'  => 'guest',
                'pass'  => 'guest',
                'vhost' => '/'
            ];

            Builder::queue($queueName, $server)->receive(function ($msg) {
                $this->message = $msg;
            });
            $response->getBody()->write(json_encode($this->message, JSON_THROW_ON_ERROR | 64 | 256));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(202);
        } catch (\RuntimeException | Exception $e) {
            $result = [
                'status'   => 'failure',
                'code'     => 400,
                'messages' => $e->getMessage(),
            ];
            $response->getBody()->write(json_encode($result, JSON_THROW_ON_ERROR | 64 | 256));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }
    }
}
