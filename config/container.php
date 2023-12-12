<?php

use Gso\Ws\Context\User\Domains\User\Interface\TokenUserRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Interface\UserAccountRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Interface\UserAddressRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Interface\UserAuthRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Interface\UserProfileRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Interface\UserRepositoryInterface;
use Gso\Ws\Context\User\Infra\Connection\GlobalConnection;
use Gso\Ws\Context\User\Infra\Connection\Interfaces\GlobalConnectionInterface;
use Gso\Ws\Context\User\Infra\User\Interface\TokenPresentationInterface;
use Gso\Ws\Context\User\Infra\User\Interface\UserPresentationInterface;
use Gso\Ws\Context\User\Infra\User\Repository\TokenUserRepository;
use Gso\Ws\Context\User\Infra\User\Repository\UserAccountRepository;
use Gso\Ws\Context\User\Infra\User\Repository\UserAddressRepository;
use Gso\Ws\Context\User\Infra\User\Repository\UserAuthRepository;
use Gso\Ws\Context\User\Infra\User\Repository\UserProfileRepository;
use Gso\Ws\Context\User\Infra\User\Repository\UserRepository;
use Gso\Ws\Web\Message\interface\RabbitMQInterface;
use Gso\Ws\Web\Message\repository\RabbitMQRepository;
use Gso\Ws\Web\Presentation\TokenManagerPresentation;
use Gso\Ws\Web\Presentation\UserPresentationRepository;
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

    BasePathMiddleware::class           => function (ContainerInterface $container) {
        return new BasePathMiddleware($container->get(App::class));
    },


    // ==============================================================
    // INTERFACES DOMAINS GSO
    // ==============================================================
    UserRepositoryInterface::class      => static function (ContainerInterface $container) {
        return $container->get(UserRepository::class);
    },
    UserAuthRepositoryInterface::class  => static function (ContainerInterface $container) {
        return $container->get(UserAuthRepository::class);
    },
    TokenUserRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(TokenUserRepository::class);
    },

    UserAddressRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(UserAddressRepository::class);
    },

    UserAccountRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(UserAccountRepository::class);
    },

    UserProfileRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(UserProfileRepository::class);
    },


    // ==============================================================
    // INTERFACES PRESENTATION  GSO
    // ==============================================================
    UserPresentationInterface::class      => static function (ContainerInterface $container) {
        return $container->get(UserPresentationRepository::class);
    },
    TokenPresentationInterface::class     => static function (ContainerInterface $container) {
        return $container->get(TokenManagerPresentation::class);
    },


    // ==============================================================
    // RABBITMQ INJECTION  GSO
    // ==============================================================

    RabbitMQInterface::class => static function (ContainerInterface $container) {
        return $container->get(RabbitMQRepository::class);
    },


// ==============================================================
// ANOTHER INTERFACES  GSO
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
