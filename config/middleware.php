<?php

use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return static function (App $app) {
    // ADICIONADO MODO DE MANIPULAR O BODY
    $app->addBodyParsingMiddleware();

    // ADICIONANDO MIDDLEWARES NAS ROTAS
    $app->addRoutingMiddleware();

    // PEGANDO BASEPATH DO PROJETO
    $app->add(BasePathMiddleware::class);

    // PEGANDO AS EXCEÇÕES E ERROS
    $app->add(ErrorMiddleware::class);
};
