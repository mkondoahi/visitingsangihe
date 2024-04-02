<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendtiket extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $isimail;
    public function __construct($isimail)
    {
        //
        $this->isimail = $isimail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('bfc.kemitraan@gmail.com')
                    ->subject('JANGAN SEBARKAN PESAN INI KE ORANG LAIN KECUALI KE PIHAK WISATA!')
                    ->view('layouts.notif_email.tiket');
    }
}
