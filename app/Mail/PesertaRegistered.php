<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PesertaRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $password;
    public $kode;
    public $fromAddress;
    public $fromName;
    public $subjek;
    public $pesan;

    public function __construct($username, $password, $kode, $fromAddress, $fromName, $subjek, $pesan)
    {
        $this->username = $username;
        $this->password = $password;
        $this->kode = $kode;
        $this->fromAddress = $fromAddress;
        $this->fromName = $fromName;
        $this->subjek = $subjek;
        $this->pesan = $pesan;  
    }

    public function build()
    {
        $subject = $this->subjek;
        $pesan2 = "Terima kasih telah mendaftar.\n\n{$this->pesan}\n\nBerikut adalah informasi login Anda:"
            . "\n\nUsername: " . $this->username
            . "\nPassword: " . $this->password
            . "\nKode Pelatihan: " . $this->kode
            . "\n\nSilakan login di website kami: http://127.0.0.1:8000/login."
            . "\n\nTerima kasih,\nTim Kami";

        return $this->subject($subject)
                    ->markdown('admin.email_peserta')
                    ->with('pesan', $pesan2);
    }
}
