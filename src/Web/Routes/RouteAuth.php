<?php

namespace Gso\Ws\Web\Routes;

use Gso\Ws\Web\Controllers\UsuarioAuthController;
use Slim\Routing\RouteCollectorProxy;

class RouteAuth
{
    public function __construct(RouteCollectorProxy $app)
    {
        $app->post('/login', UsuarioAuthController::class);
    }
}
