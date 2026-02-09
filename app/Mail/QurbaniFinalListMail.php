<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QurbaniFinalListMail extends Mailable
{
    use Queueable, SerializesModels;

    public $day;
        public $pdfContent;


    public function __construct($day, $pdfContent)
    {
        $this->day = $day;
        $this->pdfContent = $pdfContent;
    }

public function build()
{
    // $pdfPath = public_path("qurbani_pdf/Qurbani_Final_List_Day{$this->day}.pdf");
    // return $this->view('emails.qurbani_final_list')
    //             ->subject('Qurbani Final List')
    //             ->attach($pdfPath, [
    //                 'as' => "Qurbani_Final_List_Day{$this->day}.pdf",
    //                 'mime' => 'application/pdf',
    //             ]);
    return $this->subject('Qurbani Final List Day ' . $this->day)
            ->view('emails.qurbani_final_list') // Optional if you just attach
            ->attachData($this->pdfContent, 'Qurbani_Final_List_Day.pdf', [
                'mime' => 'application/pdf',
            ]);
}

}
