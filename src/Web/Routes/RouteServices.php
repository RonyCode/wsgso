<?php

namespace Gso\Ws\Web\Routes;

use Gso\Ws\Web\Controllers\MessageBrokerController;
use Slim\Routing\RouteCollectorProxy;

class RouteServices
{
    public function __construct(RouteCollectorProxy $app)
    {
        $app->get('/consume/{queue_name}', MessageBrokerController::class);
    }
}
