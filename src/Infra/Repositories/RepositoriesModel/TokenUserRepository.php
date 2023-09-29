<?php

declare(strict_types=1);

namespace Gso\Ws\Infra\Repositories\RepositoriesModel;

use Gso\Ws\Domains\User\Interface\TokenUserRepositoryInterface;
use Gso\Ws\Domains\User\Token;
use Gso\Ws\Infra\Interfaces\GlobalConnectionInterface;
use Gso\Ws\Web\Helper\ResponseError;

final class TokenUserRepository implements TokenUserRepositoryInterface
{
    use ResponseError;

    public function __construct(
        private readonly GlobalConnectionInterface $globalConnection
    ) {
    }

    public function selectTokenByCodUsuario(int $codusuario): Token
    {
        try {
            $stmt = $this->globalConnection->conn()->prepare(
                'SELECT * FROM TOKEN WHERE cod_usuario = :codUsuario AND excluido = 0'
            );
            $stmt->bindValue(':codUsuario', $codusuario, \PDO::PARAM_INT);
            $stmt->execute();
            if (0 == $stmt->rowCount()) {
                return new Token();
            }

            return $this->newObjUnidade($stmt->fetch());
        } catch (\RuntimeException) {
            $this->responseCatchError('Não foi possível encontrar token!');
        }
    }

    public function saveTokenUsuario(Token $tokenManagerModel): Token
    {
        try {
            if ($tokenManagerModel->codToken) {
                return $this->updateToken($tokenManagerModel);
            }

            return $this->insertToken($tokenManagerModel);
        } catch (\RuntimeException) {
            $this->responseCatchError('Não foi possível encontrar token!');
        }
    }

    private function newObjUnidade($data): Token
    {
        try {
            $tokenManagerModel = new Token(
                $data['cod_token'],
                $data['cod_usuario'],
                $data['token'],
                $data['refresh_token'],
                $data['data_criacao'],
                $data['data_expirar'],
                $data['excluido'],
            );
            if (!$tokenManagerModel->codUsuario) {
                throw new \RuntimeException();
            }

            return $tokenManagerModel;
        } catch (\RuntimeException) {
            $this->responseCatchError(
                'Não foi possível montar objeto Unidade dados incorretos ou objeto não encontrado no sistema!'
            );
        }
    }

    private function insertToken(Token $tokenManagerModel): Token
    {
        try {
            $stmt = $this->globalConnection
                ->conn()->prepare(
                    'INSERT INTO TOKEN (cod_usuario, token, refresh_token,data_criacao,data_expirar, excluido) 
                            VALUES (:codUsuario,:token,:refreshToken,:dataCriacao,:dataExpirar,:excluido)'
                )
            ;
            $stmt->bindValue(':codUsuario', $tokenManagerModel->codUsuario, \PDO::PARAM_INT);
            $stmt->bindValue(':token', $tokenManagerModel->token);
            $stmt->bindValue(':refreshToken', $tokenManagerModel->refreshToken);
            $stmt->bindValue(':dataCriacao', $tokenManagerModel->dataCriacao, \PDO::PARAM_INT);
            $stmt->bindValue(':dataExpirar', $tokenManagerModel->dataExpirar, \PDO::PARAM_INT);
            $stmt->bindValue(':excluido', $tokenManagerModel->excluido, \PDO::PARAM_INT);
            $stmt->execute();

            return $this->selectTokenByCodUsuario($tokenManagerModel->codUsuario);
        } catch (\RuntimeException|\JsonException) {
            $this->responseCatchError('Não foi possível salvar token!');
        }
    }

    private function updateToken(Token $tokenManagerModel): Token
    {
        try {
            $stmt = $this->globalConnection
                ->conn()->prepare(
                    'UPDATE TOKEN  SET
                token = :token, 
                data_criacao = :dataCriacao,  
                data_expirar = :dataExpirar 
                WHERE  cod_token = :codToken'
                )
            ;

            $stmt->bindValue(':codToken', $tokenManagerModel->codToken, \PDO::PARAM_INT);
            $stmt->bindValue(':token', $tokenManagerModel->token);
            $stmt->bindValue(':dataCriacao', $tokenManagerModel->dataCriacao, \PDO::PARAM_INT);
            $stmt->bindValue(':dataExpirar', $tokenManagerModel->dataExpirar, \PDO::PARAM_INT);
            $stmt->execute();
            if (0 >= $stmt->rowCount()) {
                throw new \RuntimeException();
            }

            return $this->selectTokenByCodUsuario($tokenManagerModel->codUsuario);
        } catch (\RuntimeException) {
            $this->responseCatchError('Não foi possível atualizar token!');
        }
    }
}
