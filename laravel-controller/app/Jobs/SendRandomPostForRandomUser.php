<?php

namespace App\Jobs;

use App\Mail\RandomPostForRandomUserMail;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRandomPostForRandomUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;

    private $post;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $user = User::inRandomOrder()->first();

        $post = Post::inRandomOrder()->first();

        Mail::to($user->email)->send(new RandomPostForRandomUserMail($user, $post));
    }
}
