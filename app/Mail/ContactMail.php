<?php

namespace App\Mail;

use App\Models\General;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public function __construct($r)
    {
        $this->data =$r;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $general =General::first();
        
        $r =$this->data;
        
        return $this->from($general->mail_from_address,$general->mail_from_name)->subject('Contact Mail From '.$general->mail_from_name.".")->view('mails.ContactMail', compact('r','general'));
    }
}
