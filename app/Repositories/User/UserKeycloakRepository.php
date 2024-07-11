<?php

namespace App\Repositories\User;

use App\Models\Keycloak\KeycloakUser;
use App\Models\UserKeycloak;
use App\Repositories\Repository;
use App\Traits\Repositories\CrudRepository;

class UserKeycloakRepository extends Repository
{
    use CrudRepository;

    public function __construct()
    {
        parent::__construct(new UserKeycloak());
    }

    public function createWithEntity(KeycloakUser $user)
    {
        return $this->create([
            'id' => $user->id,
            'sub' => $user->sub,
            'nik' => $user->nik,
            'email' => $user->email,
            'user_group' => json_encode($user->userGroups),
            'mobile' => $user->mobile,
            'full_name' => $user->fullName,
            'kode_identitas' => $user->kodeIdentitas,
            'first_name' => $user->firstName,
            'last_name' => $user->lastName,
        ]);
    }
}
