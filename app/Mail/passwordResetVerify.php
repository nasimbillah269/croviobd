<?php

namespace App\Mail;
use App\Models\General;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class passwordResetVerify extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public function __construct($user)
    {
        $this->data =$user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $general = General::first();
        $user =$this->data;
        return $this->from($general->mail_from_address,$general->mail_from_name)->subject("Reset Password Code Form ".$general->mail_from_name.".")->view('mails.passwordResetVerify', compact('user','general'));
    }
}
