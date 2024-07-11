<?php

namespace App\Models\Keycloak;

use App\Helper\SocialiteKeycloak;

class KeycloakUser
{

    const SESSION_KEY = "keycloak_auth_user";

    public function __construct(public string  $id,
                                public string  $sub,
                                public ?string $nik,
                                public array   $userGroups,
                                public bool    $emailVerified,
                                public string  $mobile,
                                public string  $fullName,
                                public string  $preferredUsername,
                                public string  $kodeIdentitas,
                                public string  $firstName,
                                public string  $lastName,
                                public string  $email)
    {
    }

    public static function getFromToken(string $accessToken): KeycloakUser
    {
        $user = SocialiteKeycloak::getUserFromToken($accessToken);
        return self::fromArray($user->user);
    }

    public static function fromArray(array $user): KeycloakUser
    {
        return new KeycloakUser(
            id: $user["kode_identitas"],
            sub: $user["sub"],
            nik: $user["NIK"] ?? null,
            userGroups: $user["user_group"],
            emailVerified: $user["email_verified"],
            mobile: $user["mobile"],
            fullName: $user["name"],
            preferredUsername: $user["preferred_username"],
            kodeIdentitas: $user["kode_identitas"],
            firstName: $user["given_name"],
            lastName: $user["family_name"],
            email: $user["email"],
        );
    }

    public function toArray(): array
    {
        return (array)$this;
    }
}
