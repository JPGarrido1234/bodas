<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Models\Boda;

class CompleteData extends Mailable
{
    use Queueable, SerializesModels;

    public $boda;

    public function __construct(Boda $boda)
    {
        $this->boda = $boda;
    }

    public function build()
    {
        return $this->subject('Completa los datos de tu boda')->view('mails.completedata');
    }
}
?>