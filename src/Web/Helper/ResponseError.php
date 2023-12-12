<?php

declare(strict_types=1);

namespace Gso\Ws\Web\Helper;

use JetBrains\PhpStorm\NoReturn;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

trait ResponseError
{
    private Response $response;

    /**
     * @throws JsonException
     */
    #[NoReturn]
    public function responseCatchError(
        string $message,
        int $code = 400
    ): false|string {
        http_response_code($code);
        exit(
            json_encode(
                [
                'data'    => false,
                'status'  => 'failure',
                'code'    => $code,
                'message' => $message,
                ],
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            )
        );
    }
}
