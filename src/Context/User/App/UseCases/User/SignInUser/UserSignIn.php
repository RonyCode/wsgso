<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\App\UseCases\User\SignInUser;

use Gso\Ws\Context\User\App\UseCases\UserExternal\SignInUserExternal\InputBoundaryUserExternal;
use Gso\Ws\Context\User\App\UseCases\UserExternal\SignInUserExternal\SignInUserExternal;
use Gso\Ws\Context\User\Domains\User\Events\LogUserSignIn;
use Gso\Ws\Context\User\Domains\User\Events\UserSignIn as UserSignInEvent;
use Gso\Ws\Context\User\Domains\User\Interface\TokenUserRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Interface\UserAuthRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Interface\UserRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Token;
use Gso\Ws\Context\User\Domains\User\UserAuth;
use Gso\Ws\Shared\Event\PublishEvents;
use Gso\Ws\Web\Helper\JwtHandler;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

final class UserSignIn
{
    use ResponseError;

    public function __construct(
        public readonly UserAuthRepositoryInterface $usuarioAuthRepository,
        public readonly TokenUserRepositoryInterface $tokenManagerRepository,
        public readonly PublishEvents $publishEvents,
    ) {
    }

    public function execute(InputBoundaryUserSignIn $inputValues): OutputBoundaryUserSignIn
    {
        try {
            //            SE LOGADO SISTEMA INTERNO


            $usuarioLogado = $this->usuarioAuthRepository->signIn(
                $inputValues->email,
                $inputValues->password
            );

            $usuarioByEmail = $this->usuarioAuthRepository->getUsuarioByEmail($inputValues->email);

            if (empty($usuarioLogado->id) && empty($usuarioByEmail->id) && 1 === $inputValues->isUserExternal) {
                $newObjUsuario = UserAuth::userAuthSerialize(
                    null,
                    $inputValues->email,
                    $inputValues->password,
                    $inputValues->isUserExternal,
                    date('Y-m-d H:i:s'),
                    0
                );

                // CREATE  NEW USER EXTERNAL
                $usuarioLogado = $this->usuarioAuthRepository->saveNewUsuarioAuth($newObjUsuario);
            }


            if (null === $usuarioLogado->id) {
                throw new \RuntimeException('Usuario ou senha inválido');
            }

            $token = (new JwtHandler(1200))->jwtEncode(getenv('ISS'), [
                'id'           => $usuarioLogado->id,
                'email'        => $usuarioLogado->email,
                'access_token' => true,
            ]);


            $refreshToken = (new JwtHandler(3600 * 12))->jwtEncode(getenv('ISS'), [
                'id'           => $usuarioLogado->id,
                'access_token' => false,
            ]);

            $dataToken = (new JwtHandler())->jwtDecode($token);


            // VERIFY IF EXISTS TOKEN BY CODUSUARIO IF NO SAVE NEW TOKEN
            $objTokenSalved = $this->tokenManagerRepository->selectTokenByCodUsuario((int)$usuarioLogado->id);


            $objTokenModel = new Token(
                $objTokenSalved->id ?: null,
                $objTokenSalved->idUser ?: null,
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
                    $usuarioLogado->email
                )
            );


            return new OutputBoundaryUserSignIn(
                $usuarioLogado->id,
                $usuarioLogado->email,
                $usuarioLogado->password,
                $usuarioLogado->dateCriation,
                $objTokenModel->token,
                $objTokenModel->refreshToken,
                $objTokenModel->dateCriation,
                $objTokenModel->dateExpires,
                $usuarioLogado->excluded,
            );
        } catch (RuntimeException $e) {
            $this->responseCatchError($e->getMessage(), 400);
        }
    }
}
