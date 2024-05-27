<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\ScheduleRecipient;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $randomUser = User::query()->inRandomOrder()->first();
        
        $schedule = Schedule::query()->create([
            'name' => 'Test schedule',
            'subject' => 'Test subject',
            'message' => '<h1>Test message</h1>',
            'description' => 'Test description',
            'date' => now()->format('Y-m-d'),
            'send_at' => now()->addMinutes(1)->format('H:i'),
            'send_to' => $randomUser->email,
            'send_to_user_id' => $randomUser->id,
            'created_by' => $randomUser->id,
        ]);

        $recipients = [];
        foreach ($users = User::query()->where('id', '<>', $randomUser->id)->get() as $item) {
            $recipients[] = [
                'schedule_id' => $schedule->id,
                'user_id' => $item->id,
                'email' => $item->email,
                'type' => rand(0, 1),
            ];
        }

        ScheduleRecipient::query()->insert($recipients);

        Artisan::call('app:schedule-command');
    }
}
