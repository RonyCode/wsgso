<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\App\UseCases\User\SignInUser;

use Gso\Ws\Context\User\App\UseCases\UserExternal\SignInUserExternal\InputBoundaryUserExternal;
use Gso\Ws\Context\User\App\UseCases\UserExternal\SignInUserExternal\SignInUserExternal;
use Gso\Ws\Context\User\Domains\User\Events\LogUserSignIn;
use Gso\Ws\Context\User\Domains\User\Events\UserSignIn as UserSignInEvent;
use Gso\Ws\Context\User\Domains\User\Interface\TokenUserRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Interface\UserRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Token;
use Gso\Ws\Shared\Event\PublishEvents;
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


            return new OutputBoundaryUserSignIn(
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
