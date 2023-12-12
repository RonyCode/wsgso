<?php

namespace Gso\Ws\Web\Routes;

use Gso\Ws\Web\Controllers\CitiesByStateController;
use Gso\Ws\Web\Controllers\CitiesGetAllController;
use Gso\Ws\Web\Controllers\EstadoGetAllController;
use Gso\Ws\Web\Controllers\TokenAuthController;
use Gso\Ws\Web\Controllers\UserAuthPreRegisterController;
use Gso\Ws\Web\Controllers\UserAuthController;
use Gso\Ws\Web\Controllers\UserRegisterController;
use Gso\Ws\Web\Middleware\AuthMiddleWare;
use Slim\Routing\RouteCollectorProxy;

class RouteAuth
{
    public function __construct(RouteCollectorProxy $app)
    {
        $app->post('/login', UserAuthController::class);
        $app->get('/refresh-token/{token}', TokenAuthController::class);
        $app->post('/pre-cadastro', UserAuthPreRegisterController::class);
        $app->post('/cadastro', UserRegisterController::class);
        $app->get('/estados', EstadoGetAllController::class);
        $app->get('/cidades', CitiesGetAllController::class);
        $app->get('/cidades/{state}', CitiesByStateController::class);
    }
}
