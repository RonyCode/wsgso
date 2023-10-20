<?php

declare(strict_types=1);

use Gso\Ws\Web\Controllers\MessageBrokerController;
use Gso\Ws\Web\Controllers\TokenAuthController;
use Gso\Ws\Web\Controllers\UsuarioAuthController;
use Gso\Ws\Web\Middleware\AuthMiddleWare;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../../../config/settings.php';

return static function (App $app) {
    $app->group(
        '/api',
        function (RouteCollectorProxy $group) {
            $group->group(
                '/auth',
                function (RouteCollectorProxy $group) {
                    $group->get('/refresh-token/{token}', TokenAuthController::class);
                }
            )->add(new AuthMiddleWare());

            $group->group(
                '/login',
                function (RouteCollectorProxy $app) {
                    $app->post('/auth', UsuarioAuthController::class);
                }
            );
            $group->group(
                '/amqp',
                function (RouteCollectorProxy $app) {
                    $app->get('/consume/{queue_name}', MessageBrokerController::class);
                }
            );
        }
    );
};
