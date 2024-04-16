<?php

namespace App\Models;

class User extends BaseModel
{
    protected string $table = "users";

    public function __construct()
    {
        parent::__construct();
    }
}