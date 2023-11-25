<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignIn;

use Gso\Ws\Context\User\Domains\User\Events\UserSignedEvent as UserSignInEvent;
use Gso\Ws\Context\User\Domains\User\Interface\TokenUserRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Interface\UserAuthRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Token;
use Gso\Ws\Context\User\Domains\User\UserAuth;
use Gso\Ws\Shared\Event\PublishEvents;
use Gso\Ws\Web\Helper\JwtHandler;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

final class UserAuthSignInCase
{
    use ResponseError;

    public function __construct(
        public readonly UserAuthRepositoryInterface $userAuthRepository,
        public readonly TokenUserRepositoryInterface $tokenManagerRepository,
        public readonly PublishEvents $publishEvents,
    ) {
    }

    public function execute(InputBoundaryUserAuthSignIn $inputValues): OutputBoundaryUserAuthSignIn
    {
        try {
            //            SE LOGADO SISTEMA INTERNO
            $usuarioLogado = $this->userAuthRepository->signIn(
                $inputValues->email,
                $inputValues->password
            );

            $usuarioByEmail = $this->userAuthRepository->getUserAuthByEmail($inputValues->email);


            //            VERIFICA SE NÃO EXISTE USUÁRIO E SE É EXTERNO ENTÃO CRIA NOVO USUARIO
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
                $usuarioLogado = $this->userAuthRepository->saveNewUserAuth($newObjUsuario);
            }


            if (null === $usuarioLogado->id) {
                throw new \RuntimeException('Usuario ou senha inválido');
            }

            // CRIA ACCESS TOKEN
            $token = (new JwtHandler(1200))->jwtEncode(getenv('ISS'), [
                'id'           => $usuarioLogado->id,
                'email'        => (string)$usuarioLogado->email,
                'access_token' => true,
            ]);

            // CRIA REFRESH TOKEN
            $refreshToken = (new JwtHandler(3600 * 12))->jwtEncode(getenv('ISS'), [
                'id'           => $usuarioLogado->id,
                'access_token' => false,
            ]);


            //SEPARA TEMPO DE EXPIRAÇÃO TOKEN
            $dataToken = (new JwtHandler())->jwtDecode($token);

            // VERIFY IF EXISTS TOKEN BY CODUSUARIO IF NO SAVE NEW TOKEN
            $objTokenSalved = $this->tokenManagerRepository->selectTokenByCodUsuario((int)$usuarioLogado->id);


            $objTokenModel = new Token(
                $objTokenSalved->id ?: null,
                $objTokenSalved->idUser ?: $usuarioLogado->id,
                $token,
                $refreshToken,
                $dataToken->iat,
                $dataToken->exp,
                0,
            );

            $this->publishEvents->publish(
                new UserSignInEvent($usuarioLogado->email, $usuarioLogado->id)
            );
                $this->tokenManagerRepository->saveTokenUsuario($objTokenModel) ?? throw new \RuntimeException();


            return new OutputBoundaryUserAuthSignIn(
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
