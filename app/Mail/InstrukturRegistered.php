<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstrukturRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $password;
    public $kode;
    public $fromName;
    public $subjek;

    public function __construct($username, $password, $kode, $fromName, $subjek)
    {
        $this->username = $username;
        $this->password = $password;
        $this->kode = $kode;
        $this->fromName = $fromName;
        $this->subjek = $subjek;
    }

    public function build()
    {
        $subjek = $this->subjek;
        $pesan = "Terima kasih telah bergabung dalam pelatihan yang diselenggarakan oleh Diskominfo Kota Semarang. Berikut adalah informasi login Anda:"
            . "\n\nUsername: " . $this->username
            . "\nPassword: " . $this->password
            . "\nKode Pelatihan: " . $this->kode
            . "\n\nSilakan login di website kami: http://127.0.0.1:8000/login."
            . "\n\nTerima kasih,\nTim Kami";

        return $this->subject($subjek)
                    ->markdown('admin.email_instruktur')
                    ->with('pesan', $pesan);
    }
}
