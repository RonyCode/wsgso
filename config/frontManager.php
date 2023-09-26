<?php

use DI\ContainerBuilder;
use Slim\App;
use Slim\Routing\RouteContext;

require_once __DIR__.'/../vendor/autoload.php';

// CONTAINER CRIADO
$containerBuilder = new ContainerBuilder();

// ADICONANDO DEFINIÇÕES DO CONTAINER PELO ARQUIVO IMPORTADO
$containerBuilder->addDefinitions(__DIR__.'/container.php');

// MONTANDO O BUILDER DAS DEFINIÇÕES
$container = $containerBuilder->build();

// INSTANCIANDO O APP SLIM
$app = $container->get(App::class);

// HABILITANDO CORS
$app->add(
    function ($request, $handler) {
        $routeContext = RouteContext::fromRequest($request);
        $routingResults = $routeContext->getRoutingResults();
        $methods = $routingResults->getAllowedMethods();

        return $handler->handle($request)->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
    }
);

// ADICIONANDO AS ROTAS
(require __DIR__ . '/../src/Routes/routes.php')($app);

// ADICIONANDO OS MIDDLEWARES
(require __DIR__.'/middleware.php')($app);

// ADICIONANDO AS CONFIG
(require __DIR__.'/config.php')($app);

return $app;
