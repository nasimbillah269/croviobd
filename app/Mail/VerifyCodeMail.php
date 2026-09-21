<?php

namespace App\Mail;

use App\Models\General;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $verifycode;
    public function __construct($verifycode)
    {
        $this->verifycode =$verifycode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $general = General::first();
        $verifycode =$this->verifycode;
        return $this->from($general->mail_from_address,$general->mail_from_name)->subject('Your Verify OPT Code Form '.$general->title.'.')->view('mails.VerifyCodeMail', compact('general','verifycode'));
    }
}
