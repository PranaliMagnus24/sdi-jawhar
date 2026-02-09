<?php
namespace App\Http\Controllers;
use App\Models\General;
use App\Models\User;
use App\Models\Qurbani;
use App\Models\QurbaniHisse;
use App\Models\QurbaniDay;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF; // Import the PDF facade

class PDFController extends Controller
{
     public function generatePDF($qurbani_id)
{
    $qurbaniid = base64_decode($qurbani_id);
    $qurbani = Qurbani::findOrFail($qurbaniid);
    $qurbanihisse = QurbaniHisse::where('qurbani_id', $qurbaniid)->get();
    $users = User::find($qurbani->user_id);
    $general = General::first();

    $pdf = \PDF::loadView('pdfview', [
        'qurbani' => $qurbani,
        'qurbanihisse' => $qurbanihisse,
        'users' => $users,
        'general' => $general
    ])->setPaper('A5', 'portrait');

    // Save PDF to public/qurbani_pdf
    $pdfFolder = public_path('qurbani_pdf');
    if (!file_exists($pdfFolder)) {
        mkdir($pdfFolder, 0777, true);
    }

    $pdfPath = "{$pdfFolder}/qurbani_{$qurbani->id}.pdf";
    $pdf->save($pdfPath);

    // Force download the saved file
    return response()->download($pdfPath);
}

    // public function generatePDF($qurbani_id)
    // {
    //     $qurbaniid = base64_decode($qurbani_id);
    //     $qurbani = Qurbani::find($qurbaniid);
    //     $qurbanihisse = QurbaniHisse::where('qurbani_id', $qurbaniid)->get();
    //     $users = User::find($qurbani->user_id);
    //     $general = General::first();
    //     $pdfname = $qurbani->contact_name . "_" . $qurbani->id;

    //     $customPaper = 'A5';
    //     return view('pdfview', compact('qurbani', 'qurbanihisse', 'users', 'general'));
    //     // $pdf = PDF::loadView('pdfview', [
    //     //     'qurbani' => $qurbani,
    //     //     'qurbanihisse' => $qurbanihisse,
    //     //     'users' => $users,
    //     //     'general' => General::first()
    //     // ])
    //     // ->setPaper($customPaper, 'portrait')
    //     // ->setOptions([
    //     //     'isRemoteEnabled' => true,
    //     // ]);

    //     // return $pdf->download($pdfname . '.pdf');
    // }

    // public function generatefinallist($day)
    // {

    //     $query = QurbaniHisse::query();
    //     if($day==1){
    //         $query->where('created_at','<=',date('2025-05-13 21:59:59'));
    //     }else if($day==2){
    //         $query->where('created_at','>',date('2025-05-13 21:59:59'))
    //         ->where('created_at','<=',date('2025-05-14 23:59:59'));
    //     }
    //     $qurbanihisse = $query->get()->toArray();

    //     $columns = array_chunk($qurbanihisse, 7);

    //     $customPaper = 'A4';
    //     $pdf = PDF::loadView('finallist', [
    //         'columns' => $columns
    //     ])->setPaper($customPaper, 'portrait');

    //      return $pdf->download('Qurbani_final_list.pdf');
    // }

    public function generatefinallist($day)
    {
        $qurbaniDays = Qurbani::where('qurbani_days', $day)->get();

        $columns = [];

        if ($qurbaniDays->isNotEmpty()) {
            $qurbaniIds = $qurbaniDays->pluck('id')->toArray();

            $qurbanihisse = QurbaniHisse::whereIn('qurbani_id', $qurbaniIds)->get()->toArray();
            $columns = array_chunk($qurbanihisse, 7);
        }

        $customPaper = 'A4';

        $pdf = PDF::loadView('finallist', [
            'columns' => $columns,
            'day' => $day,
        ])->setPaper($customPaper, 'portrait');

        // Define public path to qurbani_pdf directory
        $publicDirectory = public_path('qurbani_pdf');

        // Create directory if it does not exist
        if (!is_dir($publicDirectory)) {
            mkdir($publicDirectory, 0755, true);
        }

        $pdfPath = $publicDirectory . "/Qurbani_Final_List_Day{$day}.pdf";

        // Save PDF file to public/qurbani_pdf
        $pdf->save($pdfPath);

        // Optionally, return the file for download if you want (your current usage)
        return response()->download($pdfPath);
    }



        //////////////Final list day1 and day2
//     public function finalListView($day)
// {
//     $query = QurbaniHisse::query();

//     if ($day == 1) {
//         $query->where('created_at', '<=', date('2025-05-13 21:59:59'));
//     } elseif ($day == 2) {
//         $query->where('created_at', '>', date('2025-05-13 21:59:59'))
//               ->where('created_at', '<=', date('2025-05-14 23:59:59'));
//     }

//     $qurbanihisse = $query->get()->toArray();
//     $columns = array_chunk($qurbanihisse, 7);

//     return view('qurbanis.final_list', compact('columns', 'day'));
// }

public function finalListView($day)
{
    $qurbaniDays = Qurbani::where('qurbani_days', $day)->get();

    if ($qurbaniDays->isNotEmpty()) {
        $qurbaniIds = $qurbaniDays->pluck('id')->toArray();

        $qurbanihisse = QurbaniHisse::whereIn('qurbani_id', $qurbaniIds)->get()->toArray();
        $columns = array_chunk($qurbanihisse, 7);

        return view('qurbanis.final_list', compact('columns', 'day'));
    }

    return view('qurbanis.final_list', ['columns' => [], 'day' => $day]);
}



}
