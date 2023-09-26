<?php


use Gso\Ws\Domains\Contracts\TokenManagerRepositoryInterface;
use Gso\Ws\Domains\Contracts\UsuarioAuthRepositoryInterface;
use Gso\Ws\Infra\Connection\GlobalConnection;
use Gso\Ws\Infra\Interfaces\GlobalConnectionInterface;
use Gso\Ws\Infra\Interfaces\InterfacesPresentation\TokenManagerByCodUsuarioPresentationInterface;
use Gso\Ws\Infra\Interfaces\InterfacesPresentation\UsuarioAuthPresentationInterface;
use Gso\Ws\Infra\Repositories\RepositoriesModel\TokenManagerRepository;
use Gso\Ws\Infra\Repositories\RepositoriesModel\UsuarioAuthRepository;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Factory\Psr17\Psr17Factory;
use Slim\Middleware\ErrorMiddleware;

return [
    // ==============================================================
    // INSTANCES SLIM
    // ==============================================================

    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },

    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app      = $container->get(App::class);
        $settings = $container->get('settings')['error'];

        return new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)$settings['display_error_details'],
            (bool)$settings['log_errors'],
            (bool)$settings['log_error_details']
        );
    },

    BasePathMiddleware::class => function (ContainerInterface $container) {
        return new BasePathMiddleware($container->get(App::class));
    },

    // ==============================================================
    // INTERFACES MODEL SIOCB
    // ==============================================================

    UsuarioAuthRepositoryInterface::class  => static function (ContainerInterface $container) {
        return $container->get(UsuarioAuthRepository::class);
    },
    TokenManagerRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(TokenManagerRepository::class);
    },

    // ==============================================================
    // INTERFACES PRESENTATION  SIOCB
    // ==============================================================

    TokenManagerByCodUsuarioPresentationInterface::class => static function (ContainerInterface $container) {
        return $container->get(TokenManagerRepository::class);
    },
    UsuarioAuthPresentationInterface::class              => static function (ContainerInterface $container) {
        return $container->get(UsuarioAuthRepository::class);
    },

    // ==============================================================
    // ANOTHER INTERFACES  SIOCB
    // ==============================================================

    GlobalConnectionInterface::class => static function (ContainerInterface $container) {
        return $container->get(GlobalConnection::class);
    },
    ServerRequestInterface::class    => static function (ContainerInterface $container) {
        return $container->get(ServerRequest::class);
    },
    ResponseInterface::class         => static function (ContainerInterface $container) {
        return $container->get(Response::class);
    },
];
