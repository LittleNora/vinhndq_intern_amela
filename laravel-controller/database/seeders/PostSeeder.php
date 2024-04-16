<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $posts = [];

        $users = User::query()->pluck('id');

        for ($i = 0; $i < 20; $i++) {
            $posts[] = [
                'title' => fake()->sentence(),
                'content' => fake()->paragraph(),
                'user_id' => $users->random(),
            ];
        }

        Post::query()->insert($posts);
    }
}
