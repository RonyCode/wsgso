<?php

namespace Gso\Ws\App\UseCases\TokenManagerByCodUsuarioCase;

use Gso\Ws\App\Helper\JwtHandler;
use Gso\Ws\App\Helper\ResponseError;
use Gso\App\Domains\Contracts\TokenManagerRepositoryInterface;
use Gso\App\Domains\Contracts\UsuarioAuthRepositoryInterface;
use Gso\App\Domains\Models\TokenManagerModel;
use http\Exception\RuntimeException;

class TokenManagerByCodUsuarioCase
{
    use ResponseError;

    public function __construct(
        private readonly TokenManagerRepositoryInterface $tokenRepository,
        private readonly UsuarioAuthRepositoryInterface $usuarioAuthRepository,
    ) {
    }

    public function handle(InputBoundaryTokenManagerByCodUsuario $inputValues): OutputBoundaryTokenManagerByCodUsuario
    {
        try {
            $token = str_replace('+', '.', $inputValues->token);
            $tokenDecoded = (new JwtHandler())->jwtDecode($token);
            if (is_array($tokenDecoded) || empty($tokenDecoded)) {
                throw new \RuntimeException('Refresh token expirado ou inválido');
            }
            $tokenSalvo = $this->tokenRepository->selectTokenByCodUsuario($tokenDecoded->data->cod_usuario);

            $verifyRefreshToken = (new JwtHandler())->jwtDecode(
                $tokenSalvo->refreshToken
            );

            //            Se refresh token vencido retorna um erro para realizar novo login
            if (
                is_array($verifyRefreshToken)
                && 1 === $tokenSalvo->excluido
                && $tokenSalvo->token === $tokenDecoded->data->token
            ) {
                throw new \RuntimeException('Refresh token expirado ou inválido');
            }
            $usuario = $this->usuarioAuthRepository->getUsuarioById($tokenSalvo->codUsuario);

            //           Refresh token valido devolve novo acess-token com 15 min
            $tokenNovo = (new JwtHandler(1200))->jwtEncode(getenv('ISS'), [
                'cod_usuario' => $usuario->codUsuario,
                'nome' => $usuario->nome,
                'email' => $usuario->email,
                'image' => $usuario->image,
                'access_token' => true,
            ]);

            $tokenRefreshed = (new JwtHandler(1200))->jwtDecode($tokenNovo);
            $objToken = new TokenManagerModel(
                $tokenSalvo->codToken,
                $tokenSalvo->codUsuario,
                $tokenNovo,
                $tokenSalvo->refreshToken,
                $tokenRefreshed->iat,
                $tokenRefreshed->exp,
                0,
            );

            $novoToken = $this->tokenRepository->saveTokenUsuario($objToken);

            if (!$novoToken) {
                throw new RuntimeException('Não foi possível salvar novo access-token!');
            }

            //            se tudo certo retorna mesmo objeto que foi salvo
            $outPutBoundary = new OutputBoundaryTokenManagerByCodUsuario(
                $objToken->codToken,
                $objToken->codUsuario,
                $objToken->token,
                $objToken->refreshToken,
                $objToken->dataCriacao,
                $objToken->dataExpirar,
                $objToken->excluido
            );
        } catch (\RuntimeException $e) {
            $this->responseCatchError($e->getMessage());
        }

        return $outPutBoundary;
    }
}
