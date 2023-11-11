<?php

declare(strict_types=1);

use Gso\Ws\Web\Routes\RouteAuth;
use Gso\Ws\Web\Routes\RouteServices;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../../../config/settings.php';

return static function (App $app) {
    $app->group('/api', function (RouteCollectorProxy $app) {
        $app->group('/auth', function (RouteCollectorProxy $app) {
            new RouteAuth($app);
        });
    });
    $app->group('/services', function (RouteCollectorProxy $app) {
        $app->group('/amqp', function (RouteCollectorProxy $app) {
            new RouteServices($app);
        });
    });
};
