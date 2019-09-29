<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use  App\User;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $subject;
    public $body;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $subject, string $body)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return  $this->subject($this->subject)
                     ->from('ticket@omad.com')
                     ->to($this->user->email)
                     ->view('email.reminder');
    }
}
