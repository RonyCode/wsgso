<?php

namespace Gso\Ws\App\Middleware;

use Gso\Ws\App\Helper\JwtHandler;
use Gso\Ws\App\Helper\ResponseError;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleWare
{
    use ResponseError;

    /**
     * Example middleware invokable class.
     *
     * @param ServerRequest  $request PSR-7 request
     * @param RequestHandler $handler PSR-15 request handler
     *
     * @throws \JsonException
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        try {
            $httpHeader = apache_request_headers();
            if (empty($httpHeader['Authorization'])) {
                throw new \RuntimeException('Ausência de Header Authorization');
            }
            str_contains($httpHeader['Authorization'], 'Bearer ') ? $token = str_replace(
                'Bearer ',
                '',
                $httpHeader['Authorization']
            ) : $token = false;

            $responseDecode = (new JwtHandler())->jwtDecode($token);

            if (is_array($responseDecode) || false === $responseDecode->data->access_token) {
                throw new \RuntimeException();
            }

            return $handler->handle($request);
        } catch (\RuntimeException|\JsonException  $e) {
            $this->responseCatchError('Sem autorização para realizar a operação', 401);
        }
    }
}
