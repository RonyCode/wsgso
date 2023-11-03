<?php

namespace Gso\Ws\Web\Controllers;

use Gso\Ws\Web\Message\Builder;
use Gso\Ws\Web\Message\interface\RabbitMQInterface;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MessageBrokerController
{
    private Response $responseArg;
    private $message = [];

    public function __construct()
    {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $this->responseArg = $response;
            $queueName         = $args['queue_name'];
            $server            = [
                'host' => 'localhost',
                'port' => 5672,
                'user' => 'guest',
                'pass' => 'guest',
            ];


            Builder::queue($queueName, $server)->receive(function ($msg) use ($response) {
                $this->message = $msg;
            });
            $response->getBody()->write(json_encode($this->message, JSON_THROW_ON_ERROR | 64 | 256));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(202);
        } catch (\RuntimeException $e) {
            $result = [
                'status'  => 'failure',
                'code'    => 400,
                'message' => $e->getMessage(),
            ];
            $response->getBody()->write(json_encode($result, JSON_THROW_ON_ERROR | 64 | 256));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }
    }

    public function removeDuplicateKeys($key, $data)
    {
        $_data = array();

        foreach ($data as $v) {
            if (isset($_data[$v[$key]])) {
                // found duplicate
                continue;
            }
            // remember unique item
            $_data[$v[$key]] = $v;
        }
        // if you need a zero-based array
        // otherwise work with $_data
        $data = array_values($_data);

        return $data;
    }
}
