<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Attendance;
use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $hoDivision = Division::query()->create([
            'name' => 'HO',
            'is_default' => 1,
        ]);

        Division::factory(5)->create();

        $admin = \App\Models\User::query()->create([
            'name' => 'Nguyễn Duy Quang Vinh',
            'email' => 'nguyenduyquangvinh2906@gmail.com',
            'organization_email' => 'vinh.nguyenduyquang@amela.vn',
            'username' => 'vinhndq',
            'phone' => '0123456789',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'division_id' => $hoDivision->id,
        ]);

        User::factory(50)->create();

        Attendance::factory(100)->create();
    }
}
