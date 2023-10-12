<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Infra\User\Repository;

use Gso\Ws\Context\User\Domains\User\Interface\TokenUserRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Token;
use Gso\Ws\Context\User\Infra\Connection\Interfaces\GlobalConnectionInterface;
use Gso\Ws\Web\Helper\ResponseError;

final class TokenUserRepository implements TokenUserRepositoryInterface
{
    use ResponseError;

    public function __construct(
        private readonly GlobalConnectionInterface $globalConnection
    ) {
    }

    public function selectTokenByCodUsuario(int $idUser): Token
    {
        try {
            $stmt = $this->globalConnection->conn()->prepare(
                'SELECT * FROM token WHERE id_user = :idUser AND excluded = 0'
            );
            $stmt->bindValue(':idUser', $idUser, \PDO::PARAM_INT);
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
            if ($tokenManagerModel->id) {
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
                $data['id'],
                $data['id_user'],
                $data['token'],
                $data['refresh_token'],
                $data['date_criation'],
                $data['date_expires'],
                $data['excluded'],
            );
            if (! $tokenManagerModel->idUser) {
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
                    'INSERT INTO token ( id_user, token, refresh_token, date_criation, date_expires, excluded) 
                            VALUES (:idUser,:token,:refreshToken,:dateCriation,:dateExpires,:excluded)'
                );
            $stmt->bindValue(':idUser', $tokenManagerModel->id, \PDO::PARAM_INT);
            $stmt->bindValue(':token', $tokenManagerModel->token);
            $stmt->bindValue(':refreshToken', $tokenManagerModel->refreshToken);
            $stmt->bindValue(':dateCriation', $tokenManagerModel->dateCriation, \PDO::PARAM_INT);
            $stmt->bindValue(':dateExpires', $tokenManagerModel->dateExpires, \PDO::PARAM_INT);
            $stmt->bindValue(':excluded', $tokenManagerModel->excluded, \PDO::PARAM_INT);
            $stmt->execute();

            return $this->selectTokenByCodUsuario((int)$this->globalConnection->conn()->lastInsertId());
        } catch (\RuntimeException | \JsonException) {
            $this->responseCatchError('Não foi possível salvar token!');
        }
    }

    private function updateToken(Token $tokenManagerModel): Token
    {
        try {
            $stmt = $this->globalConnection
                ->conn()->prepare(
                    'UPDATE token  SET
                token = :token, 
                date_criation = :dataCriacao,  
                date_expires = :dataExpirar 
                WHERE  id = :codToken'
                );

            $stmt->bindValue(':codToken', $tokenManagerModel->id, \PDO::PARAM_INT);
            $stmt->bindValue(':token', $tokenManagerModel->token);
            $stmt->bindValue(':dataCriacao', $tokenManagerModel->dateCriation, \PDO::PARAM_INT);
            $stmt->bindValue(':dataExpirar', $tokenManagerModel->dateExpires, \PDO::PARAM_INT);
            $stmt->execute();
            if (0 >= $stmt->rowCount()) {
                throw new \RuntimeException();
            }

            return $this->selectTokenByCodUsuario($tokenManagerModel->idUser);
        } catch (\RuntimeException) {
            $this->responseCatchError('Não foi possível atualizar token!');
        }
    }
}
