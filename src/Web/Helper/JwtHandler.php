<?php

namespace Gso\Ws\Web\Helper;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

class JwtHandler
{
    protected int $issuedAt;
    protected int $expire;
    protected string $jwt_secrect;

    public function __construct(
        protected ?int $lifeTime = 3600,
    ) {
        // set your default time-zone
        date_default_timezone_set('America/Araguaina');

        $this->issuedAt = time();
        // Token Validity (3600 second = 1hr)
        $this->expire = $this->issuedAt + $lifeTime;
        // Set your secret or signature
        $this->jwt_secrect = getenv('JWT_KEY_SECRET');
    }

    // ENCODING THE TOKEN
    public function jwtEncode($pathBase, array $data): string
    {
        $token = [
            // Adding the identifier to the token (who issue the token)
            'iss' => $pathBase,
            'aud' => $pathBase,
            // Adding the current timestamp to the token, for identifying that when the token was issued.
            'iat' => $this->issuedAt,
            // Token expiration
            'exp' => $this->expire,
            // Payload
            'data' => $data,
        ];

        return JWT::encode($token, $this->jwt_secrect, 'HS256');
    }

    // DECODING THE TOKEN
    public function jwtDecode($jwt_token): \stdClass|array
    {
        try {
            return JWT::decode($jwt_token, new Key($this->jwt_secrect, 'HS256'));
        } catch (
            ExpiredException|SignatureInvalidException|
            BeforeValidException|\DomainException|\InvalidArgumentException|\UnexpectedValueException $e
        ) {
            return [false, $e->getMessage()];
        }
    }
}
