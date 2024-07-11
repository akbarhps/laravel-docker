<?php

namespace App\Repositories\User;

use App\Models\Role;
use App\Repositories\Repository;
use App\Traits\Repositories\CrudRepository;

class RoleRepository extends Repository
{
    use CrudRepository;

    public function __construct()
    {
        parent::__construct(new Role());
    }
}
