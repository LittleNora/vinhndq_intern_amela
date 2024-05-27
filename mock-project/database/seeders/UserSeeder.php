<?php

namespace Database\Seeders;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function __construct(
        private UserRepositoryInterface $modelRepo
    )
    {
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

    }
}
