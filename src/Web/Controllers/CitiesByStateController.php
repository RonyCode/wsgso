<?php

declare(strict_types=1);

namespace Gso\Ws\Web\Controllers;

use Gso\Ws\Context\User\App\UseCases\User\Address\AddressCitiesByState\AddressCitiesByStateCase;
use Gso\Ws\Context\User\App\UseCases\User\Address\AddressCitiesByState\InputBoundaryAddressCitiesByState;
use Gso\Ws\Context\User\App\UseCases\User\Address\AddressCitiesGetAll\AddressCitiesGetAllCase;
use Gso\Ws\Context\User\App\UseCases\User\Address\AddressStatesGetAll\AddressStatesGetAllCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final readonly class CitiesByStateController
{
    public function __construct(
        private AddressCitiesByStateCase $addressCitiesByStateCase,
    ) {
    }


    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $state = $args['state'];
            if (empty($state)) {
                throw new \RuntimeException('Parametros inválidos ou ausentes');
            }

            $input  = new InputBoundaryAddressCitiesByState($state);
            $output = $this->addressCitiesByStateCase->execute($input);
            $result = json_encode($output, JSON_THROW_ON_ERROR | 64 | 256);
            $response->getBody()->write($result);

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
