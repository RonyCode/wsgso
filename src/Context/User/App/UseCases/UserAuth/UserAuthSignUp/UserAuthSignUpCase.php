<?php

namespace Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignUp;

use Gso\Ws\Context\User\Domains\User\Interface\UserAuthRepositoryInterface;
use Gso\Ws\Shared\Event\PublishEvents;
use Gso\Ws\Web\Helper\JwtHandler;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

class UserAuthSignUpCase
{
    use ResponseError;

    public function __construct(
        public readonly UserAuthRepositoryInterface $usuarioAuthRepository,
        public readonly PublishEvents $publishEvents,
    ) {
    }


    public function execute(InputBoundaryUserAuthSignUp $inputValues): OutputBoundaryUserAuthSignUp
    {
        try {
            $usuarioByEmail = $this->usuarioAuthRepository->getUserAuthByEmail($inputValues->email);


            //            VERIFICA SE JÁ EXISTE USUÁRIO CADASTRADO
            if (! empty($usuarioByEmail->id)) {
                throw new RuntimeException('Usuário com email já cadastrado!');
            }


            // CRIA TOKEN PARA CADASTRO POR EMAIL
            $token = (new JwtHandler(900))->jwtEncode(getenv('ISS'), [
                'email' => $inputValues->email,
            ]);


            var_dump($token);
            exit();
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
