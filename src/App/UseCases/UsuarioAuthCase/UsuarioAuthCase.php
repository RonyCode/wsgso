<?php

declare(strict_types=1);

namespace Gso\Ws\App\UseCases\UsuarioAuthCase;

use Gso\Ws\App\Helper\JwtHandler;
use Gso\Ws\App\Helper\ResponseError;
use Gso\App\App\UseCases\UsuarioExternoAuthCase\InputBoundaryUsuarioExternoAuth;
use Gso\App\App\UseCases\UsuarioExternoAuthCase\UsuarioExternoAuthCase;
use Gso\App\Domains\Contracts\TokenManagerRepositoryInterface;
use Gso\App\Domains\Contracts\UsuarioAuthRepositoryInterface;
use Gso\App\Domains\Models\TokenManagerModel;
use RuntimeException;

final class UsuarioAuthCase
{
    use ResponseError;

    public function __construct(
        public readonly UsuarioAuthRepositoryInterface $usuarioAuthRepository,
        public readonly TokenManagerRepositoryInterface $tokenManagerRepository,
        public readonly UsuarioExternoAuthCase $usuarioExternoAuthCase,
    ) {
    }

    public function handle(InputBoundaryUsuarioAuth $inputValues): OutputBoundaryUsuarioAuth
    {
        try {
            //            SE LOGADO SISTEMA INTERNO
            $usuarioLogado = $this->usuarioAuthRepository->login(
                $inputValues->email,
                $inputValues->senha
            );
            //            SE LOGADO SISTEMA EXTERNO (GOOGLE, GITHUB. ETC)
            if (empty($usuarioLogado->codUsuario) && 1 === $inputValues->isUserExterno) {
                $inputBoundaryUsuarioExterno = new InputBoundaryUsuarioExternoAuth(
                    $inputValues->email,
                    $inputValues->senha,
                    $inputValues->nome,
                    $inputValues->image,
                    $inputValues->isUserExterno
                );

                $usuarioExternoLogado = $this->usuarioExternoAuthCase->handle($inputBoundaryUsuarioExterno);
            }

            if ((empty($usuarioLogado) || null == $usuarioLogado->codUsuario) && (empty($usuarioExternoLogado) || null == $usuarioExternoLogado->codUsuario)) {
                throw new \RuntimeException('Usuario ou senha inválido');
            }

            $token = (new JwtHandler(1200))->jwtEncode(getenv('ISS'), [
                'cod_usuario' => $usuarioLogado->codUsuario ?? $usuarioExternoLogado->codUsuario,
                'nome' => $usuarioLogado->nome ?? $usuarioExternoLogado->nome,
                'email' => $usuarioLogado->email ?? $usuarioExternoLogado->email,
                'image' => $usuarioLogado->image ?? $usuarioExternoLogado->image,
                'access_token' => true,
            ]);

            $refreshToken = (new JwtHandler(3600 * 12))->jwtEncode(getenv('ISS'), [
                'cod_usuario' => $usuarioLogado->codUsuario ?? $usuarioExternoLogado->codUsuario,
                'access_token' => false,
            ]);

            $dataToken = (new JwtHandler())->jwtDecode($token);
            $usuarioLogado->codUsuario ? $codUsuarioFiltrado = $usuarioLogado->codUsuario : $codUsuarioFiltrado = $usuarioExternoLogado->codUsuario;

            // VERIFY IF EXISTS TOKEN BY CODUSUARIO IF NO SAVE NEW TOKEN
            $objTokenSalved = $this->tokenManagerRepository->selectTokenByCodUsuario((int) $codUsuarioFiltrado);

            $objTokenModel = new TokenManagerModel(
                $objTokenSalved->codToken ?: null,
                $objTokenSalved->codUsuario ?: $codUsuarioFiltrado,
                $token,
                $refreshToken,
                $dataToken->iat,
                $dataToken->exp,
                0,
            );

            $this->tokenManagerRepository->saveTokenUsuario($objTokenModel) ?? throw new \RuntimeException();

            return new OutputBoundaryUsuarioAuth(
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
