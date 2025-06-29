<?php

namespace Artisan\Services;

use Firebase\JWT\JWT as FBJWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use RuntimeException;

class JWT
{
    // Supported algorithms
    public const string ALG_HS256 = 'HS256';
    public const string ALG_HS384 = 'HS384';
    public const string ALG_HS512 = 'HS512';
    public const string ALG_RS256 = 'RS256';
    public const string ALG_RS384 = 'RS384';
    public const string ALG_RS512 = 'RS512';
    public const string ALG_ES256 = 'ES256';
    public const string ALG_ES384 = 'ES384';
    public const string ALG_ES512 = 'ES512';
    public const string ALG_EDDSA = 'EdDSA';

    private static ?self $instance = null;

    private string $algorithm;

    private function __construct(string $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    private function __clone() {}

    private function __wakeup(): void
    {
        throw new RuntimeException("Cannot unserialize singleton");
    }

    public static function load(string $algorithm): self
    {
        self::$instance = new self($algorithm);
        return self::$instance;
    }

    public static function i(): self
    {
        if (!self::$instance) {
            throw new RuntimeException("JWT must be loaded using JWT::load(\$algorithm) before calling JWT::i()");
        }

        return self::$instance;
    }

    public function encode(string $secretKey, int $expirationSeconds, mixed $data): string
    {
        $payload = [
            "iat" => time(),
            "exp" => time() + $expirationSeconds,
            "data" => $data,
        ];

        return FBJWT::encode($payload, $secretKey, $this->algorithm);
    }

    public function decode(string $jwt, string $secretKey): ?array
    {
        $key = new Key($secretKey, $this->algorithm);

        try {
            $decoded = FBJWT::decode($jwt, $key);
        } catch (ExpiredException|\Throwable) {
            return null;
        }

        return [
            'created_at' => $decoded->iat ?? null,
            'expires_at' => $decoded->exp ?? null,
            'data' => $decoded->data ?? null,
        ];
    }
}
