<?php

namespace App\Jobs;

use App\Mail\QurbaniFinalListMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendQurbaniFinalListEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $day;

    public function __construct($email, $day)
    {
        $this->email = $email;
        $this->day = $day;
    }

    public function handle()
    {
        Mail::to($this->email)->send(new QurbaniFinalListMail($this->day));
    }
}

