<?php

declare(strict_types=1);

namespace Gso\Ws\App\UseCases\UsuarioExternoAuthCase;

use Gso\Ws\App\Helper\ResponseError;
use Gso\App\Domains\Contracts\TokenManagerRepositoryInterface;
use Gso\App\Domains\Contracts\UsuarioAuthRepositoryInterface;
use Gso\App\Domains\Models\UsuarioModel;
use Gso\Ws\Domains\ValuesObjects\Email;
use RuntimeException;

final class UsuarioExternoAuthCase
{
    use ResponseError;

    public function __construct(
        public readonly UsuarioAuthRepositoryInterface $usuarioAuthRepository,
        public readonly TokenManagerRepositoryInterface $tokenManagerRepository,
    ) {
    }

    public function handle(InputBoundaryUsuarioExternoAuth $inputValues): OutputBoundaryUsuarioExternoAuth
    {
        try {
            $usuarioByEmail = $this->usuarioAuthRepository->getUsuarioByEmail($inputValues->email);
            if (empty($usuarioByEmail->senhaExterna)) {
                $usuarioExterno = null;
                $newObjUsuario = new UsuarioModel(
                    $usuarioByEmail->codUsuario,
                    null,
                    $inputValues->nome,
                    new Email($inputValues->email),
                    null,
                    $inputValues->senha,
                    date('Y-m-d H:i:s'),
                    $inputValues->image,
                    0,
                );

                //                CREATE NEW USER  OR UPDATE USER INTERNO WITH PASSWORD
                $usuarioExternoCriadoOuAtualizado = $this->usuarioAuthRepository->saveNewUsuarioAuth($newObjUsuario);

                if ($usuarioExternoCriadoOuAtualizado->codUsuario) {
                    $usuarioExterno = $this->usuarioAuthRepository->loginUsuarioExterno(
                        (string) $usuarioExternoCriadoOuAtualizado->email,
                        $inputValues->senha
                    );
                }
            } else {
                $usuarioExterno = $this->usuarioAuthRepository->loginUsuarioExterno(
                    $inputValues->email,
                    $inputValues->senha
                );
            }

            return new OutputBoundaryUsuarioExternoAuth(
                $usuarioExterno->codUsuario,
                $usuarioExterno->cpf,
                $usuarioExterno->nome,
                $usuarioExterno->email,
                $usuarioExterno->senha,
                $usuarioExterno->senhaExterna,
                $usuarioExterno->dataCadastro,
                $usuarioExterno->image,
                $usuarioExterno->excluido,
            );
        } catch (RuntimeException) {
            $this->responseCatchError('Usuário  externo não encontrado', 401);
        }
    }
}
