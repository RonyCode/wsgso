<?php

declare(strict_types=1);

namespace Gso\Ws\App\UseCases\User\SignInUser;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Gso\Ws\App\UseCases\UserExternal\SignInUserExternal\InputBoundaryUserExternal;
use Gso\Ws\App\UseCases\UserExternal\SignInUserExternal\SignInUserExternal;
use Gso\Ws\Domains\Event\PublishEvents;
use Gso\Ws\Domains\User\Events\LogUserSignIn;
use Gso\Ws\Domains\User\Events\UserSignIn as UserSignInEvent;
use Gso\Ws\Domains\User\Interface\TokenUserRepositoryInterface;
use Gso\Ws\Domains\User\Interface\UserRepositoryInterface;
use Gso\Ws\Domains\User\Token;
use Gso\Ws\Web\Helper\JwtHandler;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

final class UserSignIn
{
    use ResponseError;

    public function __construct(
        public readonly UserRepositoryInterface $usuarioAuthRepository,
        public readonly TokenUserRepositoryInterface $tokenManagerRepository,
        public readonly SignInUserExternal $usuarioExternoAuthCase,
        public readonly PublishEvents $publishEvents,
    ) {
    }

    public function execute(InputBoundaryUserSignIn $inputValues): OutputBoundaryUserSignIn
    {
        try {
            //            SE LOGADO SISTEMA INTERNO
            $usuarioLogado = $this->usuarioAuthRepository->login(
                $inputValues->email,
                $inputValues->senha
            );

            //            SE LOGADO SISTEMA EXTERNO (GOOGLE, GITHUB. ETC)
            if (empty($usuarioLogado->codUsuario) && 1 === $inputValues->isUserExterno) {
                $inputBoundaryUsuarioExterno = new InputBoundaryUserExternal(
                    $inputValues->email,
                    $inputValues->senha,
                    $inputValues->nome,
                    $inputValues->image,
                    $inputValues->isUserExterno
                );

                $usuarioExternoLogado = $this->usuarioExternoAuthCase->execute($inputBoundaryUsuarioExterno);
            }

            if (
                (empty($usuarioLogado) || null == $usuarioLogado->codUsuario) &&
                (empty($usuarioExternoLogado) || null == $usuarioExternoLogado->codUsuario)
            ) {
                throw new \RuntimeException('Usuario ou senha inválido');
            }

            $token = (new JwtHandler(1200))->jwtEncode(getenv('ISS'), [
                'cod_usuario'  => $usuarioLogado->codUsuario ?? $usuarioExternoLogado->codUsuario,
                'nome'         => $usuarioLogado->nome ?? $usuarioExternoLogado->nome,
                'email'        => $usuarioLogado->email ?? $usuarioExternoLogado->email,
                'image'        => $usuarioLogado->image ?? $usuarioExternoLogado->image,
                'access_token' => true,
            ]);


            $refreshToken = (new JwtHandler(3600 * 12))->jwtEncode(getenv('ISS'), [
                'cod_usuario'  => $usuarioLogado->codUsuario ?? $usuarioExternoLogado->codUsuario,
                'access_token' => false,
            ]);

            $dataToken = (new JwtHandler())->jwtDecode($token);
            $usuarioLogado->codUsuario ?
                $codUsuarioFiltrado = $usuarioLogado->codUsuario :
                $codUsuarioFiltrado = $usuarioExternoLogado->codUsuario;

            // VERIFY IF EXISTS TOKEN BY CODUSUARIO IF NO SAVE NEW TOKEN
            $objTokenSalved = $this->tokenManagerRepository->selectTokenByCodUsuario((int)$codUsuarioFiltrado);
            var_dump($dataToken);

            $objTokenModel = new Token(
                $objTokenSalved->codToken ?: null,
                $objTokenSalved->codUsuario ?: $codUsuarioFiltrado,
                $token,
                $refreshToken,
                $dataToken->iat,
                $dataToken->exp,
                0,
            );

                $this->tokenManagerRepository->saveTokenUsuario($objTokenModel) ?? throw new \RuntimeException();

            $this->publishEvents->addListener(new LogUserSignIn());
            $this->publishEvents->publish(
                new UserSignInEvent(
                    $usuarioLogado->email ?? $usuarioExternoLogado->email
                )
            );

            $teste = new OutputBoundaryUserSignIn(
                $usuarioLogado->codUsuario,
                $usuarioLogado->cpf,
                $usuarioLogado->nome,
                $usuarioLogado->email,
                $usuarioLogado->senha,
                $usuarioLogado->dataCadastro,
                $usuarioLogado->image,
                $objTokenModel->token,
                $objTokenModel->refreshToken,
                $objTokenModel->dataCriacao,
                $objTokenModel->dataExpirar,
                $usuarioLogado->excluido,
            );

            return $teste;
            new OutputBoundaryUserSignIn(
                $usuarioLogado->codUsuario ?? $usuarioExternoLogado->codUsuario,
                $usuarioLogado->cpf ?? $usuarioExternoLogado->cpf,
                $usuarioLogado->nome ?? $usuarioExternoLogado->nome,
                $usuarioLogado->email ?? $usuarioExternoLogado->email,
                $usuarioLogado->senha ?? $usuarioExternoLogado->senha,
                $usuarioLogado->dataCadastro ?? $usuarioExternoLogado->dataCadastro,
                $usuarioLogado->image ?? $usuarioExternoLogado->image,
                $objTokenModel->token,
                $objTokenModel->refreshToken,
                $objTokenModel->dataCriacao,
                $objTokenModel->dataExpirar,
                $usuarioLogado->excluido,
            );
        } catch (RuntimeException) {
            $this->responseCatchError('Usuário não encontrado', 400);
        }
    }
}
