<?php

namespace App\Jobs;

use App\Mail\QurbaniFinalListMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Qurbani;
use App\Models\QurbaniHisse;
use PDF;

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
        ini_set('memory_limit', '5120M');

        $qurbaniDays = Qurbani::where('qurbani_days', 'LIKE', '%' . $this->day . '%')->get();

        $columns = [];

        if ($qurbaniDays->isNotEmpty()) {
            $qurbaniIds = $qurbaniDays->pluck('id')->toArray();

            $qurbanihisse = QurbaniHisse::whereIn('qurbani_id', $qurbaniIds)->get()->toArray();
            $columns = array_chunk($qurbanihisse, 7);
        }

        $customPaper = 'A4';

        $pdf = PDF::loadView('finallist', [
            'columns' => $columns,
            'day' => $this->day,
        ])->output();

        $emailids = [];
        $emailids[] = $this->email;
        $emailids[] = 'sdinashikoffice@gmail.com';
        $emailids[] = 'tamboli.ejaz98@gmail.com';
        Mail::to($emailids)->send(new QurbaniFinalListMail($this->day,$pdf));
    }
}

