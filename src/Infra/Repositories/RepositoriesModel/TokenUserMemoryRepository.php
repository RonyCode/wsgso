<?php

namespace Gso\Ws\Infra\Repositories\RepositoriesModel;

use Gso\Ws\Domains\User\Exceptions\TokenNotFound;
use Gso\Ws\Domains\User\Interface\TokenUserRepositoryInterface;
use Gso\Ws\Domains\User\Token;

class TokenUserMemoryRepository implements TokenUserRepositoryInterface
{
    private array $token = [];



    public function saveTokenUsuario(Token $tokenManagerModel): Token
    {
        $this->token[] = $tokenManagerModel;

        return $this->token[0];
    }

    public function selectTokenByCodUsuario(int $codusuario): Token
    {
        $this->saveTokenUsuario(new Token(null, $codusuario));
        $tokenFiltered = array_filter(
            $this->token,
            static fn(Token $token) => $token->codUsuario === $codusuario
        );

        if (count($tokenFiltered) === 0) {
            throw new TokenNotFound($codusuario);
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
