<?php

declare(strict_types=1);

namespace Gso\Ws\App\UseCases\UserExternal\SignInUserExternal;

use Gso\Ws\Domains\User\Interface\TokenUserRepositoryInterface;
use Gso\Ws\Domains\User\Interface\UserRepositoryInterface;
use Gso\Ws\Domains\User\User;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

final class SignInUserExternal
{
    use ResponseError;


    public function __construct(
        public readonly UserRepositoryInterface $usuarioAuthRepository,
        public readonly TokenUserRepositoryInterface $tokenManagerRepository,
    ) {
    }


    public function execute(InputBoundaryUserExternal $inputValues): OutputBoundaryUserExternal
    {
        try {
            $usuarioByEmail = $this->usuarioAuthRepository->getUsuarioByEmail($inputValues->email);
            if (empty($usuarioByEmail->senhaExterna)) {
                $usuarioExterno = null;
                $newObjUsuario  = User::userSerialize(
                    $usuarioByEmail->codUsuario,
                    null,
                    $inputValues->nome,
                    $inputValues->email,
                    null,
                    $inputValues->senha,
                    date('Y-m-d H:i:s'),
                    $inputValues->image,
                    0,
                );

                // CREATE NEW USER  OR UPDATE USER INTERNO WITH PASSWORD
                $usuarioExternoCriadoOuAtualizado = $this->usuarioAuthRepository->saveNewUsuarioAuth($newObjUsuario);

                if ($usuarioExternoCriadoOuAtualizado->codUsuario) {
                    $usuarioExterno = $this->usuarioAuthRepository->loginUsuarioExterno(
                        (string)$usuarioExternoCriadoOuAtualizado->email,
                        $inputValues->senha
                    );
                }
            } else {
                $usuarioExterno = $this->usuarioAuthRepository->loginUsuarioExterno(
                    $inputValues->email,
                    $inputValues->senha
                );
            }

            return new OutputBoundaryUserExternal(
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
