<?php

namespace Gso\Ws\Context\User\App\UseCases\Token\GetTokenByCodUser;

use Gso\Ws\Context\User\Domains\User\Interface\TokenUserRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Interface\UserAuthRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Token;
use Gso\Ws\Web\Helper\JwtHandler;
use Gso\Ws\Web\Helper\ResponseError;
use http\Exception\RuntimeException;

class RefreshTokenByTokenCase
{
    use ResponseError;

    public function __construct(
        private readonly TokenUserRepositoryInterface $tokenRepository,
        private readonly UserAuthRepositoryInterface $usuarioAuthRepository,
    ) {
    }

    public function execute(InputBoundaryRefreshTokenCase $inputValues): OutputBoundaryRefreshTokenCase
    {
        try {
            $token        = str_replace('+', '.', $inputValues->token);
            $tokenDecoded = (new JwtHandler())->jwtDecode($token);

            if (is_array($tokenDecoded)) {
                throw new \RuntimeException('Refresh token expirado ou inválido');
            }

            $tokenSalvo = $this->tokenRepository->selectTokenByCodUsuario($tokenDecoded->data->id);

            $verifyRefreshToken = (new JwtHandler())->jwtDecode(
                $tokenSalvo->refreshToken
            );


            //            Se refresh token vencido retorna um erro para realizar novo login
            if (
                is_array($verifyRefreshToken)
                && 1 === $tokenSalvo->excluded
                && $tokenSalvo->token === $tokenDecoded->data->token
            ) {
                throw new \RuntimeException('Refresh token expirado ou inválido');
            }
            $usuario = $this->usuarioAuthRepository->getUserAuthById($tokenSalvo->idUser);


            //           Refresh token valido devolve novo access-token com 15 min
            $tokenNovo = (new JwtHandler(1200))->jwtEncode(getenv('ISS'), [
                'id_user'      => $usuario->id,
                'email'        => $usuario->email,
                'access_token' => true,
            ]);

            $tokenRefreshed = (new JwtHandler(1200))->jwtDecode($tokenNovo);

            $objToken = new Token(
                $tokenSalvo->id,
                $tokenSalvo->idUser,
                $tokenNovo,
                $tokenSalvo->refreshToken,
                $tokenRefreshed->iat,
                $tokenRefreshed->exp,
                0,
            );

            $novoToken = $this->tokenRepository->saveTokenUsuario($objToken);

            if (! $novoToken) {
                throw new RuntimeException('Não foi possível salvar novo access-token!');
            }

            //            se tudo certo retorna mesmo objeto que foi salvo
            $outPutBoundary = new OutputBoundaryRefreshTokenCase(
                $objToken->id,
                $objToken->idUser,
                $objToken->token,
                $objToken->refreshToken,
                $objToken->dateCriation,
                $objToken->dateExpires,
                $objToken->excluded
            );
        } catch (\RuntimeException $e) {
            $this->responseCatchError($e->getMessage());
        }

        return $outPutBoundary;
    }
}
