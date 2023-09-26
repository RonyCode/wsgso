<?php

declare(strict_types=1);

use Gso\Ws\App\Controllers\OcorrenciaByDateController;
use Gso\Ws\App\Controllers\OcorrenciaByIdController;
use Gso\Ws\App\Controllers\OcorrenciaGetAllController;
use Gso\Ws\App\Controllers\TokenAuthController;
use Gso\Ws\App\Controllers\UsuarioAuthController;
use Gso\Ws\App\Middleware\AuthMiddleWare;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../../config/settings.php';

return static function (App $app) {
    $app->group(
        '/api',
        function (RouteCollectorProxy $group) {
            $group->group(
                '/auth',
                function (RouteCollectorProxy $group) {
                    $group->get('/refresh-token/{token}', TokenAuthController::class);
                    $group->group(
                        '/ocorrencias',
                        function (RouteCollectorProxy $app) {
                            $app->get('', OcorrenciaGetAllController::class);
                            $app->post('/por-id', OcorrenciaByIdController::class);
                            $app->post('/por-data', OcorrenciaByDateController::class);
                        }
                    );
                }
            )->add(new AuthMiddleWare());
            $group->group(
                '/login',
                function (RouteCollectorProxy $app) {
                    $app->post('/auth', UsuarioAuthController::class);
                }
            );
        }
    );
};
