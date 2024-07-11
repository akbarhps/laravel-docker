<?php

namespace App\Models\Keycloak;

use App\Helper\SocialiteKeycloak;
use Illuminate\Support\Carbon;

class KeycloakToken
{
    const SESSION_KEY = "keycloak_auth_token";

    private Carbon $createdAt;

    public function __construct(public string $accessToken,
                                public int    $expiresInSeconds,
                                public int    $refreshExpiresInSeconds,
                                public string $refreshToken,
                                public string $tokenType,
                                public string $idToken,
                                public int    $notBeforePolicy,
                                public string $sessionState,
                                public string $scope)
    {
        $this->createdAt = Carbon::now();
    }

    public function isAccessTokenExpired(): bool
    {
        return $this->createdAt->addSeconds($this->expiresInSeconds)->isPast();
    }

    public function isRefreshTokenExpired(): bool
    {
        return $this->createdAt->addSeconds($this->refreshExpiresInSeconds)->isPast();
    }

    public function refreshToken(): void
    {
        $token = SocialiteKeycloak::refreshToken($this->refreshToken);

        $this->accessToken = $token->token;
        $this->refreshToken = $token->refreshToken;
        $this->expiresInSeconds = $token->expiresIn;
    }

    public static function fromArray(array $token): KeycloakToken
    {
        return new KeycloakToken(
            accessToken: $token["access_token"],
            expiresInSeconds: $token["expires_in"],
            refreshExpiresInSeconds: $token["refresh_expires_in"],
            refreshToken: $token["refresh_token"],
            tokenType: $token["token_type"],
            idToken: $token["id_token"],
            notBeforePolicy: $token["not-before-policy"],
            sessionState: $token["session_state"],
            scope: $token["scope"],
        );
    }

    public function toArray(): array
    {
        return (array)$this;
    }
}
