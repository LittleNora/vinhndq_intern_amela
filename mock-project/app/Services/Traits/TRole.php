<?php

namespace App\Services\Traits;

use App\Enums\Role;

trait TRole
{
    public function isAdmin()
    {
        return $this->role == Role::ADMIN->value;
    }
}
