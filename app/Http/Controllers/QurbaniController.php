<?php

namespace App\Http\Controllers;

use App\Models\Qurbani;
use App\Models\Qurbani2024;
use App\Models\General;
use Illuminate\View\View;
use App\Models\QurbaniHisse;
use App\Models\QurbaniHisse2024;
use App\Models\QurbaniDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use App\Jobs\GenerateQurbaniPdfJob;
use App\Jobs\SendSMSMessageJob;
use App\Jobs\SendWhatsAppMessageJob;
use App\Exports\QurbaniExport;
use Maatwebsite\Excel\Facades\Excel;
use function PHPUnit\Framework\returnArgument;
use Illuminate\Support\Facades\DB;



class QurbaniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:qurbani-list|qurbani-create|qurbani-edit|qurbani-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:qurbani-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:qurbani-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:qurbani-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
{
    $user = auth()->user();
    $name = $request->input('contact_name');
    $mobile = $request->input('mobile');
    $receiptbook = $request->input('receipt_book');
    $year = $request->input('year', 2025);
    $sortBy = $request->input('sort_by', 'id');
    $order = $request->input('order', 'desc');

    if ($year == 2025) {
        $query = Qurbani::with('details')->whereYear('created_at', $year);
    } else {
        $query = Qurbani2024::with('details2024')->whereYear('created_at', $year);
    }

    // 🔒 Restrict to user's own data if not admin
    if (!$user->hasRole('Admin')) {
        $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere(function ($sub) {
                  $sub->whereNull('user_id')
                      ->where('is_approved', 1);
              });
        });
    }

    // Optional filter by collector (only applies for admins)
    if ($request->filled('collected_by')) {
        $query->where('user_id', $request->collected_by);
    }

    if ($name) {
        $query->where('contact_name', 'like', "%$name%");
    }

    if ($mobile) {
        $query->where('mobile', 'like', "%$mobile%");
    }

    if ($receiptbook) {
        $query->where('receipt_book', 'like', "%$receiptbook%");
    }

    if (in_array($sortBy, ['contact_name', 'mobile', 'id']) && in_array($order, ['asc', 'desc'])) {
        $query->orderBy($sortBy, $order);
    }

    $qurbanis = $query->get();

    // Total Hissa Calculation
    $totalHissa = $year == 2025
        ? $qurbanis->pluck('details')->flatten()->sum('hissa')
        : $qurbanis->pluck('details2024')->flatten()->sum('hissa');

    // Pagination
    $page = $request->input('page', 1);
    $perPage = 50;
    $paginatedQurbanis = new \Illuminate\Pagination\LengthAwarePaginator(
        $qurbanis->slice(($page - 1) * $perPage, $perPage),
        $qurbanis->count(),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    // Load list of users (for collected_by dropdown, etc.)
    $collectedUsers = User::select('id', 'name')->get();

    return view('qurbanis.index', compact('totalHissa', 'collectedUsers', 'year'))
        ->with('qurbanis', $paginatedQurbanis)
        ->with('i', ($page - 1) * $perPage);
}





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $qurbani = new Qurbani();
        return view('qurbanis.create', compact('qurbani'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
       $rules = [
    'contact_name' => 'required|string|max:255',
    'receipt_book' => 'nullable|string|max:255',
    'mobile' => 'required|numeric|digits:10',
    'alternative_mobile' => 'nullable|numeric|digits:10',
    'payment_type' => 'required|in:Cash,RazorPay',
    'qurbani_days' => 'required|string',
    'transaction_number' => 'required_if:payment_type,RazorPay|string|nullable|max:255',
    'upload_payment' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:2048',
    'gender' => 'required|array',
    'hissa' => 'nullable|array',
    'name' => 'required|array',
    'name.*' => 'required|string|max:255',
];

        $messages = [
            'transaction_number.required_if' => 'Transaction number is required for Online payments.',
        ];

        $request->validate($rules, $messages);

        // Map RazorPay to Online
        $paymentStatus = [
            'Cash' => 'Cash Paid',
            'RazorPay' => 'Paid Online',
        ];

        // Create Qurbani
        $qurbani = new Qurbani();
        $qurbani->user_id = Auth::id() ?? 0;
        $qurbani->created_by = Auth::id() ?? 0;
        $qurbani->contact_name = $request->contact_name;
        $qurbani->mobile = $request->mobile;
        $qurbani->alternative_mobile = $request->alternative_mobile;
        $qurbani->qurbani_days = $request->qurbani_days;
        $qurbani->receipt_book = $request->receipt_book;
        $qurbani->payment_type = $request->payment_type === 'RazorPay' ? 'Online' : 'Cash';
        $qurbani->payment_status = $paymentStatus[$request->payment_type];
        $qurbani->transaction_number = $request->transaction_number ?? null;
        $qurbani->total_amount = $request->total_amount;

        // if ($request->hasFile('upload_payment')) {
        //     $file = $request->file('upload_payment');
        //     $filename = time() . '_' . $file->getClientOriginalName();
        //     $file->move(public_path('uploads/payment_proofs'), $filename);
        //     $qurbani->upload_payment = 'uploads/payment_proofs/' . $filename;
        // }

        if ($qurbani->save()) {
            // Save Hissa data
            foreach ($request->name as $key => $value) {
                $qurbanihisse = new QurbaniHisse();
                $qurbanihisse->user_id = Auth::id() ?? 0;
                $qurbanihisse->qurbani_id = $qurbani->id;
                $qurbanihisse->name = $value;
                $qurbanihisse->aqiqah = !empty($request->aqiqah[$key]) ? '1' : '0';
                $qurbanihisse->gender = $request->gender[$key] ?? null;
                $qurbanihisse->hissa = (int) ($request->hissa[$key] ?? 1);
                $qurbanihisse->save();
            }

            // $this->SendSMSMessage($request->mobile, $qurbani);
            //GenerateQurbaniPdfJob::dispatch($qurbani->id, $request->mobile);

            $pdfUrl = 'https://sdijwr.mytasks.in/qurbani-pdf-url/' . base64_encode($qurbani->id);
            //$pdfUrl = 'https://sdi.mytasks.in/generate-pdf/'.base64_encode($qurbani->id);

            $this->WhatsAppMessage($request->mobile, $pdfUrl);
            // Now dispatch the WhatsApp & SMS Jobs
            // SendWhatsAppMessageJob::dispatch($request->mobile, $pdfUrl);
            // return redirect()->route('qurbani.thanyou')->with('success', 'Qurbani Created Successfully!');
        }
        return redirect()->route('qurbani.thanyou')->with('error', 'Qurbani Creation Failed.');
    }

    ////Display Qurbani Data with Hissa
    public function show(Qurbani $qurbani): View
    {
        $hisses = QurbaniHisse::where('qurbani_id', $qurbani->id)->get();
        return view('qurbanis.show', compact('qurbani', 'hisses'));
    }


    ///Edit Qurbani Data with Hissa
    public function edit($id): View
    {
        $qurbani = Qurbani::findOrFail($id);
        $qurbanihisse = QurbaniHisse::where('qurbani_id', $qurbani->id)->get();
        $isEditMode = true;
        return view('qurbanis.edit', compact('qurbani', 'qurbanihisse', 'isEditMode'));
    }


    ////Update Qurbani Data with Hissa
    public function update(Request $request, $id): RedirectResponse
    {
        $rules = [
            'contact_name' => 'required|string|max:255',
            'receipt_book' => 'nullable|string|max:255',
            'mobile' => 'required|numeric|digits:10',
            'alternative_mobile' => 'nullable|numeric|digits:10',
            'payment_type' => 'required|in:Cash,RazorPay',
            'qurbani_days' => 'nullable|string',
            'transaction_number' => 'required_if:payment_type,RazorPay|string|nullable|max:255',
            'upload_payment' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:2048',
            'aqiqah' => 'nullable|array',
             'gender' => 'required|array',
    'gender.*' => function ($attribute, $value, $fail) use ($request) {
        $index = explode('.', $attribute)[1];
        $aqiqah = $request->input('aqiqah');

        // Check if Aqiqah is checked for the corresponding index
        if (isset($aqiqah[$index]) && $aqiqah[$index] == 1 && empty($value)) {
            $fail('Gender is required when Aqiqah is checked.');
        }
    },
            'hissa' => 'nullable|array',
            'name' => 'required|array',
            'name.*' => 'required|string|max:255',
        ];

        $messages = [
            'transaction_number.required_if' => 'Transaction number is required for Online payments.',
        ];

        $request->validate($rules, $messages);

        $paymentStatus = [
            'Cash' => 'Cash Paid',
            'RazorPay' => 'Paid Online',
        ];

        $qurbani = Qurbani::findOrFail($id);
        $qurbani->updated_by = Auth::id();
        $qurbani->contact_name = $request->contact_name;
        $qurbani->mobile = $request->mobile;
        $qurbani->alternative_mobile = $request->alternative_mobile;
        $qurbani->qurbani_days = $request->qurbani_days;
        $qurbani->receipt_book = $request->receipt_book;
        $qurbani->payment_type = $request->payment_type === 'RazorPay' ? 'Online' : 'Cash';
        $qurbani->payment_status = $paymentStatus[$request->payment_type];
        $qurbani->transaction_number = $request->transaction_number ?? null;
        $qurbani->total_amount = $request->total_amount;

        // if ($request->hasFile('upload_payment')) {
        //     $file = $request->file('upload_payment');
        //     $filename = time() . '_' . $file->getClientOriginalName();
        //     $file->move(public_path('uploads/payment_proofs'), $filename);
        //     $qurbani->upload_payment = 'uploads/payment_proofs/' . $filename;
        // }

        if ($qurbani->save()) {
            // Delete old Hissa data
            QurbaniHisse::where('qurbani_id', $qurbani->id)->delete();

            // Save new Hissa data
            foreach ($request->name as $key => $value) {
                $qurbanihisse = new QurbaniHisse();
                $qurbanihisse->user_id = Auth::id() ?? 0;
                $qurbanihisse->qurbani_id = $qurbani->id;
                $qurbanihisse->name = $value;
                $qurbanihisse->aqiqah = !empty($request->aqiqah[$key]) ? '1' : '0';
                $qurbanihisse->gender = $request->gender[$key] ?? null;
                $qurbanihisse->hissa = (int) ($request->hissa[$key] ?? 1);
                $qurbanihisse->save();
            }

            // Generate new PDF and dispatch jobs
        //    $this->SendSMSMessage($request->mobile, $qurbani);
            $pdfUrl = 'https://sdijwr.mytasks.in/qurbani-pdf-url/' . base64_encode($qurbani->id);
            $this->WhatsAppMessage($request->mobile, $pdfUrl);
            // GenerateQurbaniPdfJob::dispatch($qurbani->id, $request->mobile);

            return redirect()->route('qurbani.thanyou')->with('success', 'Qurbani Updated Successfully!');
        }

        return redirect()->route('qurbani.thanyou')->with('error', 'Qurbani Update Failed.');
    }



    ////////Delete  Qurbani Data with Data
    public function destroy(Qurbani $qurbani): RedirectResponse
    {
        $qurbani->delete();
        $qurbanihisse = QurbaniHisse::where('qurbani_id', $qurbani->id)->delete();

        return redirect()->route('qurbanis.index')
            ->with('success', 'Qurbani Details deleted successfully');
    }
    public function showlist($listNumber)
    {
        $allColumns = $this->getAllColumns(); // however you're getting the full dataset
        $perList = 200;

        $sliced = array_slice($allColumns, ($listNumber - 1) * $perList, $perList);

        return view('finallist', [
            'columns' => $sliced,
            'listNo' => $listNumber
        ]);
    }
    public function archive2024()
    {
        $qurbanis = Qurbani::with('details')
            ->whereYear('created_at', 2024)
            ->latest()
            ->paginate(15);

        $collectedUsers = User::all();
        return view('qurbanis.archive', compact('qurbanis', 'collectedUsers'));
    }


    public function guestSubmissions()
    {
        $qurbanis = Qurbani::whereNull('user_id')->where('is_approved', 0)->latest()->get();
        return view('qurbanis.guest_submissions', compact('qurbanis'));
    }
    public function approveGuest($id)
    {
        $qurbani = Qurbani::with('hissas')->findOrFail($id);

        $qurbani->user_id = Auth::id(); // Who approved it
        $qurbani->is_approved = 1;
        $qurbani->save();

        // Update all hissas also
        QurbaniHisse::where('qurbani_id', $qurbani->id)->update(['user_id' => Auth::id()]);

        return redirect()->back()->with('success', 'Approved Successfully');
    }

    //////Pdf Generate Manually
    public function generatePDF($qurbani_id)
    {
        $qurbaniid = base64_decode($qurbani_id);
        $qurbani = Qurbani::with('user')->findOrFail($qurbaniid);
        $qurbanihisse = QurbaniHisse::where('qurbani_id', $qurbaniid)->get();
        $users = User::find($qurbani->user_id);
        $general = General::first();
        $logoPath = public_path('logourdu.png');
        $qrPath = public_path('qrcode.jpg');
        $footerImgPath = public_path('DailyPatti.png');

        $pdf = \PDF::loadView('pdfview', [
            'qurbani' => $qurbani,
            'qurbanihisse' => $qurbanihisse,
            'users' => $users,
            'general' => $general,
            'logoPath' => $logoPath,
            'qrPath' => $qrPath,
            'footerImgPath' => $footerImgPath,
        ])->setPaper('A5', 'portrait');

        return $pdf->download('qurbani' . $qurbaniid . '.pdf');
    }


    /////AutoComplete data name mobile
    public function suggest(Request $request)
    {
        $search = $request->get('query');
        $field = $request->get('field');

        $query = Qurbani2024::query()->with('details2024');

        if ($field === 'contact_name') {
            $query->where('contact_name', 'LIKE', "%{$search}%");
        } elseif ($field === 'mobile') {
            $query->where('mobile', 'LIKE', "%{$search}%");
        }

        $results = $query->select('id', 'contact_name', 'mobile', 'payment_type', 'receipt_book')
            ->take(10)
            ->get();

        $formatted = $results->map(function ($item) use ($field) {
            return [
                'label' => $field === 'contact_name' ? $item->contact_name : $item->mobile,
                'value' => $field === 'contact_name' ? $item->contact_name : $item->mobile,
                'contact_name' => $item->contact_name,
                'mobile' => $item->mobile,
                'payment_type' => $item->payment_type,
                'receipt_book' => $item->receipt_book,
                'hisses' => $item->details2024,
            ];
        });

        return response()->json($formatted);
    }


    // private function SendSMSMessage($mobile, $qurbani)
    // {
    //     $authKey = "453232Aptrsemu682f1d3aP1";
    //     $senderId = "SDINSK";
    //     $templateId = "1707171791448316743";

    //     $message = "ईदे कुर्बा के मौके पर सुन्नीदावते इस्लामी शाख नाशिक की जानीब से तमाम अहले सुन्नत की खिदमत मे इजतेमाई कुर्बानी का नजम रखा गया है जिस का फी हिस्सा 1500/- है बुकिंग के लीए मुबल्लीगिन से राबता करे. कुर्बानी नाशिक के बाहर मुबल्लीगिन की निगरानी मे होंगी FROM SUNNI DAWTE ISLAMI BRANCH NASHIK";

    //     $apiUrl = "http://control.bestsms.co.in/api/sendhttp.php";
    //     $postdata = [
    //         'authkey' => $authKey,
    //         'mobiles' => $mobile,
    //         'sender' => $senderId,
    //         'route' => 4,
    //         'country' => 91,
    //         'DLT_TE_ID' => $templateId,
    //         'message' => $message,
    //         'unicode' => 1,
    //     ];

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $apiUrl);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

    //     $response = curl_exec($ch);
    //     if (curl_errno($ch)) {
    //         \Log::error("SMS CURL error: " . curl_error($ch));
    //     }
    //     curl_close($ch);


    //     \Log::info("SMS API Response: " . $response);
    // }



    //  private function SendSMSMessage($mobile, $qurbani)
// {
//     $authKey = "74499AuRsBHOF65828953P1";
//     $senderId = "NSKFST";
//     $templateId = "1207162399931698582";

    //     $message = "Dear {$qurbani->contact_name},\nYour Qurbani has been successfully registered on Eid.\nThank you\nCall\n-Nashik First";

    //     $apiUrl = "http://control.bestsms.co.in/api/sendhttp.php";
//     $postdata = [
//         'authkey'    => $authKey,
//         'mobiles'    => $mobile,
//         'sender'     => $senderId,
//         'route'      => 4,
//         'country'    => 91,
//         'DLT_TE_ID'  => $templateId,
//         'message'    => $message,
//     ];

    //     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $apiUrl);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

    //     $response = curl_exec($ch);
//     if (curl_errno($ch)) {
//         \Log::error("SMS CURL error: " . curl_error($ch));
//     }
//     curl_close($ch);
//     \Log::info("SMS API Response: " . $response);
// }

    public function exportQurbani()
    {
        return Excel::download(new QurbaniExport, 'qurbanis.xlsx');
    }


    /////////////Thank you page
    public function thankYou()
    {
        $general = General::first();

        return view('qurbanis.thank_your', compact('general'));
    }
    ////////New PDF URL
    public function newpdfUrl($qurbani_id)
    {
        $queid = base64_decode($qurbani_id);
        $qurbani = Qurbani::with('user')->findOrFail($queid);
        if(!$qurbani){
            return '<h1>Oops! It looks like there’s no data to show.</h1>';
        }

        $qurbanihisse = QurbaniHisse::where('qurbani_id', $qurbani->id)->get();
        $users = User::find($qurbani->user_id);
        $general = General::first();
        $logoPath = asset('logourdu.png');
        $qrPath = asset('qrcode.jpg');
        $footerImgPath = asset('DailyPatti.png');

        $pdfUrl = 'https://sdijwr.mytasks.in/generate-pdf/' . base64_encode($qurbani->id);
        return view('respdfview', compact('qurbani', 'logoPath', 'qrPath', 'footerImgPath', 'general', 'qurbanihisse', 'pdfUrl'));

    }


    private function WhatsAppMessage($mobile, $pdfUrl)
    {
        $apiKey = "68400ea593ee4cc098ef960d9c5c5c47";
        $apiUrl = "https://whatsappnew.bestsms.co.in/wapp/v2/api/send";

        $postData = [
            'apikey' => $apiKey,
            'mobile' => $mobile,
            'msg' => "ईदे कुर्बा बहुत मुबारक हो
आपने ईदे कुर्बा के मौके पर कुर्बानी के हिस्सों के जरिए अपनी प्यारी तहरीक SDI का ताऊन किया अल्लाह आपको खूब बरकते अता फरमाए.. आमीन ( SDI NASHIK )
\n$pdfUrl",
            //'pdf' => $pdfUrl
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return "Curl error: " . curl_error($ch);
        }

        curl_close($ch);

        return $response;
    }


////////////Qurbani Dashboard
//     public function qurbaniDashboard()
// {
//     $query = User::select('users.id', 'users.name', DB::raw('COUNT(qurbanis.user_id) as qurbani_booked'))
//                 ->leftJoin('qurbanis', 'users.id', '=', 'qurbanis.user_id');

//     if (!in_array(Auth::user()->roles[0]->name, ['Admin'])) {
//         $query->where('qurbanis.user_id', Auth::id());
//     }

//     $query->groupBy('users.id', 'users.name');
//     $usersWithQurbaniCount = $query->get();

//     $baseQuery = Qurbani::query();
//     if (!in_array(Auth::user()->roles[0]->name, ['Admin'])) {
//         $baseQuery->where('user_id', Auth::id());
//     }

//     $receiptcount           = (clone $baseQuery)->count();
//     $cashCollection         = (clone $baseQuery)->where('payment_type', 'Cash')->sum('total_amount');
//     $onlineCollection       = (clone $baseQuery)->where('payment_type', 'Online')->sum('total_amount');
//     $totalQurbaniCollection = $cashCollection + $onlineCollection;
//     $cashReceipts           = (clone $baseQuery)->where('payment_type', 'Cash')->count();
//     $onlineReceipts         = (clone $baseQuery)->where('payment_type', 'Online')->count();

//     $query = QurbaniHisse::query();
//     if (!in_array(Auth::user()->roles[0]->name, ['Admin'])) {
//         $query->where('user_id', Auth::id());
//     }
//     $qurbanihisse = $query->count();

//     return view('qurbanis.qurbani_dashboard', compact(
//         'usersWithQurbaniCount',
//         'receiptcount',
//         'qurbanihisse',
//         'cashCollection',
//         'onlineCollection',
//         'totalQurbaniCollection',
//         'cashReceipts',
//         'onlineReceipts'
//     ));
// }

public function qurbaniDashboard()
{
    // Fetch qurbani booked count for users
    $query = User::select('users.id', 'users.name', DB::raw('COUNT(qurbanis.user_id) as qurbani_booked'))
                ->leftJoin('qurbanis', 'users.id', '=', 'qurbanis.user_id');

    if (!in_array(Auth::user()->roles[0]->name, ['Admin'])) {
        $query->where('qurbanis.user_id', Auth::id());
    }

    $query->groupBy('users.id', 'users.name');
    $usersWithQurbaniCount = $query->get();

    // Base Qurbani query for totals
    $baseQuery = Qurbani::query();
    if (!in_array(Auth::user()->roles[0]->name, ['Admin'])) {
        $baseQuery->where('user_id', Auth::id());
    }

    $receiptcount           = (clone $baseQuery)->count();
    $cashCollection         = (clone $baseQuery)->where('payment_type', 'Cash')->sum('total_amount');
    $onlineCollection       = (clone $baseQuery)->where('payment_type', 'Online')->sum('total_amount');
    $totalQurbaniCollection = $cashCollection + $onlineCollection;
    $cashReceipts           = (clone $baseQuery)->where('payment_type', 'Cash')->count();
    $onlineReceipts         = (clone $baseQuery)->where('payment_type', 'Online')->count();

    // Total hisse count
    $query = QurbaniHisse::query();
    if (!in_array(Auth::user()->roles[0]->name, ['Admin'])) {
        $query->where('user_id', Auth::id());
    }
    $qurbanihisse = $query->count();

    // Day-wise hisse count
    $dayWiseHisseQuery = QurbaniHisse::select('qurbanis.qurbani_days', DB::raw('COUNT(qurbani_hisses.id) as hisse_count'))
        ->join('qurbanis', 'qurbanis.id', '=', 'qurbani_hisses.qurbani_id');

    if (!in_array(Auth::user()->roles[0]->name, ['Admin'])) {
        $dayWiseHisseQuery->where('qurbani_hisses.user_id', Auth::id());
    }

    $dayWiseHisseQuery->groupBy('qurbanis.qurbani_days');
    $dayWiseHisseCounts = $dayWiseHisseQuery->pluck('hisse_count', 'qurbani_days');

    return view('qurbanis.qurbani_dashboard', compact(
        'usersWithQurbaniCount',
        'receiptcount',
        'qurbanihisse',
        'cashCollection',
        'onlineCollection',
        'totalQurbaniCollection',
        'cashReceipts',
        'onlineReceipts',
        'dayWiseHisseCounts'
    ));
}


}
