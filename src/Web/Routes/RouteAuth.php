<?php

namespace Gso\Ws\Web\Routes;

use Gso\Ws\Web\Controllers\TokenAuthController;
use Gso\Ws\Web\Controllers\UsuarioAuthCadastroController;
use Gso\Ws\Web\Controllers\UsuarioAuthController;
use Slim\Routing\RouteCollectorProxy;

class RouteAuth
{
    public function __construct(RouteCollectorProxy $app)
    {
        $app->post('/login', UsuarioAuthController::class);
        $app->get('/refresh-token/{token}', TokenAuthController::class);
        $app->get('/pre-cadastro/{token}', UsuarioAuthCadastroController::class);
    }
}
