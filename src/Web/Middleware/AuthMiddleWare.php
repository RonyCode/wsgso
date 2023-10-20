<?php

namespace Gso\Ws\Web\Middleware;

use Gso\Ws\Web\Helper\JwtHandler;
use Gso\Ws\Web\Helper\ResponseError;
use JsonException;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleWare
{
    use ResponseError;

    /**
     *
     * @param ServerRequest $request
     * @param RequestHandler $handler
     *
     * @return Response
     * @throws JsonException
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
        } catch (\RuntimeException | \JsonException  $e) {
            $this->responseCatchError('Sem autorização para realizar a operação', 401);
        }
    }
}
