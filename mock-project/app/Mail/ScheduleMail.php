<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ScheduleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $schedule;

    /**
     * Create a new message instance.
     */
    public function __construct($schedule)
    {
        //
        $this->schedule = $schedule;
    }

    public function build()
    {
        $email = $this->subject($this->schedule->subject)
            ->view('emails.schedule', [
                'content' => $this->schedule->message,
            ]);

        foreach ($this->schedule->attachments as $attachment) {
            $email->attach($attachment->path, [
                'as' => $attachment['original_name'],
                'mime' => $attachment['mime_type']
            ]);
        }

        return $email;
    }
}
