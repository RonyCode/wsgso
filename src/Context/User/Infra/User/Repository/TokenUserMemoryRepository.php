<?php

namespace Gso\Ws\Context\User\Infra\User\Repository;

use Gso\Ws\Context\User\Domains\User\Exceptions\TokenNotFound;
use Gso\Ws\Context\User\Domains\User\Interface\TokenUserRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Token;

class TokenUserMemoryRepository implements TokenUserRepositoryInterface
{
    private array $token = [];



    public function saveTokenUsuario(Token $tokenManagerModel): Token
    {
        $this->token[] = $tokenManagerModel;

        return $this->token[0];
    }

    public function selectTokenByCodUsuario(int $idUser): Token
    {
        $this->saveTokenUsuario(new Token(null, $idUser));
        $tokenFiltered = array_filter(
            $this->token,
            static fn(Token $token) => $token->codUsuario === $idUser
        );

        if (count($tokenFiltered) === 0) {
            throw new TokenNotFound($idUser);
        }

        if (count($tokenFiltered) > 1) {
            throw new \RuntimeException();
        }

        return $tokenFiltered[0];
    }

    public function buscarTodos(): array
    {
        return $this->token;
    }


}
