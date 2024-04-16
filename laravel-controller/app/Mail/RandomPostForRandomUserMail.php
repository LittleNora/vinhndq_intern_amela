<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RandomPostForRandomUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $post;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $post)
    {
        //
        $this->user = $user;

        $this->post = $post;
    }

    /**
     * Build the message.
     */
    public function build(): RandomPostForRandomUserMail
    {
        return $this->subject('May be you will like this post?')
            ->view('mails.random-post-for-random-user');
    }
}
