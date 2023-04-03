<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Models\DocSigned;

class SignDoc extends Mailable
{
    use Queueable, SerializesModels;

    public $doc_signed;

    public function __construct(DocSigned $doc_signed)
    {
        $this->doc_signed = $doc_signed;
    }

    public function build()
    {
        return $this->subject('Nuevos documentos para firmar')->view('mails.signdocs');
    }
}
?>