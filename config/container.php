<?php

use Gso\App\Domains\Contracts\AreaRepositoryInterface;
use Gso\App\Domains\Contracts\CidadeRepositorioInterface;
use Gso\App\Domains\Contracts\EnderecoOcorrenciaRepositoryInterface;
use Gso\App\Domains\Contracts\EnderecoRepositoryInterface;
use Gso\App\Domains\Contracts\EstadoRepositoryInterface;
use Gso\App\Domains\Contracts\FuncionarioRepositoryInterface;
use Gso\App\Domains\Contracts\NaturezaOcorrenciaRepositoryInterface;
use Gso\App\Domains\Contracts\OcorrenciaRepositoryInterface;
use Gso\App\Domains\Contracts\SubNaturezaRepositoryInterface;
use Gso\App\Domains\Contracts\TipoLocalRepositoryInterface;
use Gso\App\Domains\Contracts\TokenManagerRepositoryInterface;
use Gso\App\Domains\Contracts\UnidadeRepositoryInterface;
use Gso\App\Domains\Contracts\UsuarioAuthRepositoryInterface;
use Gso\App\Domains\Contracts\ViaturaServicoRepositoryInterface;
use Gso\App\Infra\Connection\GlobalConnection;
use Gso\App\Infra\Interfaces\GlobalConnectionInterface;
use Gso\App\Infra\Interfaces\InterfacesPresentation\AreaByIdPresentationInterface;
use Gso\App\Infra\Interfaces\InterfacesPresentation\CidadeByIdPresentationInterface;
use Gso\App\Infra\Interfaces\InterfacesPresentation\EnderecoByIdPresentationInterface;
use Gso\App\Infra\Interfaces\InterfacesPresentation\EnderecoOcorrenciaByIdPresentationInterface;
use Gso\App\Infra\Interfaces\InterfacesPresentation\EstadoByIdPresentationInterface;
use Gso\App\Infra\Interfaces\InterfacesPresentation\FuncionarioByIdPresentationInterface;
use Gso\App\Infra\Interfaces\InterfacesPresentation\NaturezaOcorrenciaByIdPresentationInterface;
use Gso\App\Infra\Interfaces\InterfacesPresentation\OcorrenciaAllPresentationInterface;
use Gso\App\Infra\Interfaces\InterfacesPresentation\OcorrenciaByDatePresentationInterface;
use Gso\App\Infra\Interfaces\InterfacesPresentation\OcorrenciaByIdPresentationInterface;
use Gso\App\Infra\Interfaces\InterfacesPresentation\SubNaturezaOcorrenciaByIdPresentationInterface;
use Gso\App\Infra\Interfaces\InterfacesPresentation\TokenManagerByCodUsuarioPresentationInterface;
use Gso\App\Infra\Interfaces\InterfacesPresentation\UnidadeByIdPresentationInterface;
use Gso\App\Infra\Interfaces\InterfacesPresentation\ViaturaServicoByIdPresentationInterface;
use Gso\Ws\Infra\Repositories\RepositoriesModel\AreaRepository;
use Gso\Ws\Infra\Repositories\RepositoriesModel\CidadeRepository;
use Gso\Ws\Infra\Repositories\RepositoriesModel\EnderecoOcorrenciaRepository;
use Gso\Ws\Infra\Repositories\RepositoriesModel\EnderecoRepository;
use Gso\Ws\Infra\Repositories\RepositoriesModel\EstadoRepository;
use Gso\Ws\Infra\Repositories\RepositoriesModel\FuncionarioRepository;
use Gso\Ws\Infra\Repositories\RepositoriesModel\NaturezaOcorrenciaRepository;
use Gso\Ws\Infra\Repositories\RepositoriesModel\OcorrenciaRepository;
use Gso\Ws\Infra\Repositories\RepositoriesModel\SubNaturezaRepository;
use Gso\Ws\Infra\Repositories\RepositoriesModel\TipoLocalRepository;
use Gso\Ws\Infra\Repositories\RepositoriesModel\TokenManagerRepository;
use Gso\Ws\Infra\Repositories\RepositoriesModel\UnidadeRepository;
use Gso\Ws\Infra\Repositories\RepositoriesModel\UsuarioAuthRepository;
use Gso\Ws\Infra\Repositories\RepositoriesModel\ViaturaServicoRepository;
use Gso\App\Infra\Repositories\RepositoriesPresentation\AreaByIdPresentation;
use Gso\App\Infra\Repositories\RepositoriesPresentation\CidadeByIdPresentation;
use Gso\App\Infra\Repositories\RepositoriesPresentation\EnderecoByIdPresentation;
use Gso\App\Infra\Repositories\RepositoriesPresentation\EnderecoOcorrenciaByIdPresentation;
use Gso\App\Infra\Repositories\RepositoriesPresentation\EstadoByIdPresentation;
use Gso\App\Infra\Repositories\RepositoriesPresentation\FuncionarioByIdPresentation;
use Gso\App\Infra\Repositories\RepositoriesPresentation\NaturezaOcorrenciaByIdPresentation;
use Gso\App\Infra\Repositories\RepositoriesPresentation\OcorrenciaAllPresentation;
use Gso\App\Infra\Repositories\RepositoriesPresentation\OcorrenciaByDatePresentation;
use Gso\App\Infra\Repositories\RepositoriesPresentation\OcorrenciaByIdPresentation;
use Gso\App\Infra\Repositories\RepositoriesPresentation\SubNaturezaOcorrenciaByIdPresentation;
use Gso\App\Infra\Repositories\RepositoriesPresentation\UnidadeByIdPresentation;
use Gso\App\Infra\Repositories\RepositoriesPresentation\ViaturaServicoByIdPresentation;
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
        return require __DIR__.'/settings.php';
    },
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },

    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);
        $settings = $container->get('settings')['error'];

        return new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool) $settings['display_error_details'],
            (bool) $settings['log_errors'],
            (bool) $settings['log_error_details']
        );
    },

    BasePathMiddleware::class => function (ContainerInterface $container) {
        return new BasePathMiddleware($container->get(App::class));
    },

    // ==============================================================
    // INTERFACES MODEL SIOCB
    // ==============================================================
    OcorrenciaRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(OcorrenciaRepository::class);
    },
    EnderecoRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(EnderecoRepository::class);
    },
    EnderecoOcorrenciaRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(EnderecoOcorrenciaRepository::class);
    },
    EstadoRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(EstadoRepository::class);
    },
    CidadeRepositorioInterface::class => static function (ContainerInterface $container) {
        return $container->get(CidadeRepository::class);
    },
    FuncionarioRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(FuncionarioRepository::class);
    },
    NaturezaOcorrenciaRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(NaturezaOcorrenciaRepository::class);
    },
    TipoLocalRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(TipoLocalRepository::class);
    },
    SubNaturezaRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(SubNaturezaRepository::class);
    },
    AreaRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(AreaRepository::class);
    },
    UnidadeRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(UnidadeRepository::class);
    },
    ViaturaServicoRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(ViaturaServicoRepository::class);
    },
    UsuarioAuthRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(UsuarioAuthRepository::class);
    },
    TokenManagerRepositoryInterface::class => static function (ContainerInterface $container) {
        return $container->get(TokenManagerRepository::class);
    },

    // ==============================================================
    // INTERFACES PRESENTATION  SIOCB
    // ==============================================================
    FuncionarioByIdPresentationInterface::class => static function (ContainerInterface $container) {
        return $container->get(FuncionarioByIdPresentation::class);
    },

    EnderecoByIdPresentationInterface::class => static function (ContainerInterface $container) {
        return $container->get(EnderecoByIdPresentation::class);
    },
    EnderecoOcorrenciaByIdPresentationInterface::class => static function (ContainerInterface $container) {
        return $container->get(EnderecoOcorrenciaByIdPresentation::class);
    },
    EstadoByIdPresentationInterface::class => static function (ContainerInterface $container) {
        return $container->get(EstadoByIdPresentation::class);
    },
    OcorrenciaByIdPresentationInterface::class => static function (ContainerInterface $container) {
        return $container->get(OcorrenciaByIdPresentation::class);
    },
    OcorrenciaByDatePresentationInterface::class => static function (ContainerInterface $container) {
        return $container->get(OcorrenciaByDatePresentation::class);
    },
    OcorrenciaAllPresentationInterface::class => static function (ContainerInterface $container) {
        return $container->get(OcorrenciaAllPresentation::class);
    },
    SubNaturezaOcorrenciaByIdPresentationInterface::class => static function (ContainerInterface $container) {
        return $container->get(SubNaturezaOcorrenciaByIdPresentation::class);
    },
    NaturezaOcorrenciaByIdPresentationInterface::class => static function (ContainerInterface $container) {
        return $container->get(NaturezaOcorrenciaByIdPresentation::class);
    },
    AreaByIdPresentationInterface::class => static function (ContainerInterface $container) {
        return $container->get(AreaByIdPresentation::class);
    },
    CidadeByIdPresentationInterface::class => static function (ContainerInterface $container) {
        return $container->get(CidadeByIdPresentation::class);
    },
    UnidadeByIdPresentationInterface::class => static function (ContainerInterface $container) {
        return $container->get(UnidadeByIdPresentation::class);
    },
    ViaturaServicoByIdPresentationInterface::class => static function (ContainerInterface $container) {
        return $container->get(ViaturaServicoByIdPresentation::class);
    },
    TokenManagerByCodUsuarioPresentationInterface::class => static function (ContainerInterface $container) {
        return $container->get(TokenManagerRepository::class);
    },

    // ==============================================================
    // ANOTHER INTERFACES  SIOCB
    // ==============================================================

    GlobalConnectionInterface::class => static function (ContainerInterface $container) {
        return $container->get(GlobalConnection::class);
    },
    ServerRequestInterface::class => static function (ContainerInterface $container) {
        return $container->get(ServerRequest::class);
    },
    ResponseInterface::class => static function (ContainerInterface $container) {
        return $container->get(Response::class);
    },
];
