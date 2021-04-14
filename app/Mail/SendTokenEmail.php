<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTokenEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $token;
    protected $app_id;
    protected $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($app_id, $email, $token)
    {
        $this->token = $token;
        $this->app_id = $app_id;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      $url = url("api/$this->app_id/auth/registration/confirmation?email=$this->email&token=$this->token");
      return $this->subject('Email verification')
      ->html("Link: <a href='$url'>$url</a>");
    }
}
