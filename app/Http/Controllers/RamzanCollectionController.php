<?php

namespace App\Http\Controllers;

use App\Models\DonationCategory;
use App\Models\General;
use App\Models\RamzanCollection;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RamzanCollectionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = RamzanCollection::query();

        if (! empty($user->roles) && isset($user->roles[0]) && $user->roles[0]->name !== 'Admin') {
            $query->where('user_id', $user->id);
        }

        // Filters
        $query->when($request->name, fn ($q) => $q->where('name', 'like', "%{$request->name}%"))
            ->when($request->contact, fn ($q) => $q->where('contact', 'like', "%{$request->contact}%"))
            ->when($request->receipt_book, fn ($q) => $q->where('receipt_book', 'like', "%{$request->receipt_book}%"))
            ->when($request->donationcategory, fn ($q) => $q->where('donationcategory', $request->donationcategory))
            ->when($request->payment_mode, fn ($q) => $q->where('payment_mode', $request->payment_mode))
            ->when($request->collected_by, fn ($q) => $q->where('user_id', $request->collected_by));

        // Sorting
        $allowedSortFields = ['date', 'name', 'amount'];
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('id', 'desc');
        }

        $collections = $query->paginate(10)->appends($request->all());
        $categories = DonationCategory::all();
        $collectedUsers = User::all();

        return view('ramzan.collectionlist', compact('collections', 'categories', 'collectedUsers'));
    }

    public function create()
    {
        // Get the latest collection entry based on ID
        $latestCollection = RamzanCollection::orderBy('id', 'desc')->first();

        // Generate next receipt book ID (start from 1000 if no records exist)
        $receiptBookId = $latestCollection ? $latestCollection->id + 1 : 1000;

        // Fetch all donation categories
        $categories = DonationCategory::all();

        return view('ramzan.collection', compact('categories', 'receiptBookId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'receipt_book' => 'nullable|string|max:255',
            'contact' => 'required|numeric|digits:10',
            'date' => 'required|date',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
            'donationcategory' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_mode' => 'required|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'msg_send' => 'nullable|boolean',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['msg_send'] = 0;

        $collection = RamzanCollection::create($data);
        $general = General::first();

        $logoPath = public_path('logourdu.png');
        $qrPath = public_path('qrcode.jpg');
        $dailyPattiPath = public_path('DailyPatti.png');
        $pdfUrl = 'https://sdijwr.mytasks.in/collection/view/'.base64_encode($collection->id);

        // $this->sendWhatsAppMessage($request->contact, $pdfUrl);
        $whatsappResponse = $this->sendWhatsAppMessage($request->contact, $pdfUrl);

        $responseData = json_decode($whatsappResponse, true);

        // dd($responseData);

        if (isset($responseData['status']) && $responseData['status'] == 'success' && $responseData['statuscode'] == 200) {
            $collection->msg_send = 1;
        } else {
            $collection->msg_send = 0;
        }

        $collection->save();

        return redirect()->route('collection.thankyou')->with('success', 'Collection created & PDF sent successfully.');
    }

    private function sendWhatsAppMessage($mobile, $pdfUrl)
    {
        $apiKey = '68400ea593ee4cc098ef960d9c5c5c47';
        $apiUrl = 'https://whatsappnew.bestsms.co.in/wapp/v2/api/send';

        $message = "आप ने तहेरीक सुन्नी दावते इस्लामी की इमदाद फरमाई हम आपका शुक्रिया अदा करते है. अल्लाह आपके इमदाद को कुबुल फरमाए और आपको बेहतर सिलाह अता फरमाए आमीन
जज़ाकल्लाहु खैर

$pdfUrl";

        $postData = [
            'apikey' => $apiKey,
            'mobile' => $mobile,
            'msg' => $message,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return json_encode([
                'status' => 'error',
                'message' => curl_error($ch),
            ]);
        }

        curl_close($ch);

        return $response;
    }

    // Show the form for editing an existing FAQ
    public function edit($id)
    {
        $categories = DonationCategory::all();
        $collection = RamzanCollection::findOrFail($id); // Find the FAQ by ID

        return view('ramzan.collectionedit', compact('collection', 'categories')); // Pass data to the edit view
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'receipt_book' => 'nullable|string|max:255',
            'contact' => 'required|numeric|digits:10',
            'date' => 'required|date',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
            'donationcategory' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_mode' => 'required|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
        ]);

        $collection = RamzanCollection::findOrFail($id);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['msg_send'] = 0;

        $collection->update($data);

        // public url
        $pdfUrl = 'https://sdijwr.mytasks.in/collection/view/'.base64_encode($collection->id);

        // SEND WHATSAPP MESSAGE
        $whatsappResponse = $this->sendWhatsAppMessage($collection->contact, $pdfUrl);

        $responseData = json_decode($whatsappResponse, true);

        if (
            isset($responseData['status']) &&
            $responseData['status'] === 'success' &&
            $responseData['statuscode'] == 200
        ) {
            $collection->msg_send = 1;
        } else {
            $collection->msg_send = 0;
        }

        $collection->save();

        return redirect()
            ->route('collection.thankyou')
            ->with('success', 'Collection updated & WhatsApp message sent successfully.');
    }

    // Delete a collection
    public function destroy($id)
    {
        $collection = RamzanCollection::findOrFail($id);
        if (Auth::id() !== $collection->user_id &&
            (! isset(Auth::user()->roles[0]) || Auth::user()->roles[0]->name !== 'Admin')) {
            return redirect()->route('collectionlist')->with('error', 'Unauthorized access.');
        }

        $collection->delete();

        return redirect()->route('collectionlist')->with('success', 'Collection deleted successfully.');
    }

    // View a collection
    public function view($id)
    {
        // Decode base64 encoded id
        $decodedId = base64_decode($id);
        $collection = RamzanCollection::findOrFail($decodedId);
        $general = General::first();
        $logoPath = asset('SDI_Jwr.jpeg');
        $qrPath = asset('qrcode.jpg');
        $dailyPattiPath = asset('sdi_ftr.jpg');

        $pdfUrl = route('collection.pdf', base64_encode($collection->id));

        return view(
            'ramzan.view',
            compact(
                'collection',
                'general',
                'logoPath',
                'qrPath',
                'dailyPattiPath',
                'pdfUrl'
            )
        );
    }

    // Generate PDF
    public function generatePDF($id)
    {
        // Decode base64 encoded id
        $decodedId = base64_decode($id);
        $collection = RamzanCollection::findOrFail($decodedId);
        $general = General::first();

        // $logoPath = public_path('logourdu.png');
        // $qrPath = public_path('qrcode.jpg');
        // $dailyPattiPath = public_path('DailyPatti.png');
        $logoPath = asset('SDI_Jwr.jpeg');
        $qrPath = asset('qrcode.jpg');
        $dailyPattiPath = asset('sdi_ftr.jpg');

        $pdf = Pdf::loadView(
            'ramzan.pdf',
            compact('collection', 'logoPath', 'qrPath', 'dailyPattiPath', 'general')
        )->setPaper('A5', 'portrait');

        // direct download
        return $pdf->download('Ramzan_Receipt_'.$collection->id.'.pdf');
    }

    public function export(Request $request)
    {
        if (! isset(Auth::user()->roles[0]) || Auth::user()->roles[0]->name !== 'Admin') {
            abort(403, 'Unauthorized Action.');
        }

        $fileName = 'ramadan_collection_list.csv';
        $query = RamzanCollection::query();

        // ✅ Apply Filters Based on Request
        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }
        if ($request->filled('contact')) {
            $query->where('contact', 'like', '%'.$request->contact.'%');
        }
        if ($request->filled('receipt_book')) {
            $query->where('receipt_book', 'like', '%'.$request->receipt_book.'%');
        }
        if ($request->filled('donationcategory')) {
            $query->where('donationcategory', $request->donationcategory);
        }
        if ($request->filled('payment_mode')) {
            $query->where('payment_mode', $request->payment_mode);
        }
        // ✅ Get Filtered Data
        $collections = $query->with('user')
            ->select('name', 'contact', 'date', 'donationcategory', 'payment_mode', 'amount', 'user_id')
            ->get();

        // ✅ Calculate Totals from Filtered Data
        $totalReceipts = $collections->count();
        $totalAmount = $collections->sum('amount');

        $cashCollections = $collections->where('payment_mode', 'Cash');
        $onlineCollections = $collections->where('payment_mode', 'Online');
        $notSelectedCollections = $collections->whereNull('payment_mode');

        $totalCashAmount = $cashCollections->sum('amount');
        $totalCashCount = $cashCollections->count();

        $totalOnlineAmount = $onlineCollections->sum('amount');
        $totalOnlineCount = $onlineCollections->count();

        $totalNotSelectedAmount = $notSelectedCollections->sum('amount');
        $totalNotSelectedCount = $notSelectedCollections->count();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
        ];

        $callback = function () use ($collections, $totalReceipts, $totalAmount, $totalCashAmount, $totalCashCount, $totalOnlineAmount, $totalOnlineCount, $totalNotSelectedAmount, $totalNotSelectedCount) {
            $file = fopen('php://output', 'w');

            // ✅ CSV Headers
            fputcsv($file, ['S.No', 'Name', 'Contact', 'Date', 'Donation Category', 'Payment Mode', 'Amount', 'Collected By']);

            // ✅ Export Filtered Data
            $serial = 1;
            foreach ($collections as $collection) {
                fputcsv($file, [
                    $serial++,
                    $collection->name,
                    "\t".$collection->contact,
                    date('d-m-Y', strtotime($collection->date)),
                    $collection->donationcategory,
                    $collection->payment_mode ?? 'N/A',
                    sprintf('%.2f', $collection->amount),  // ✅ Fixed large number display issue
                    $collection->user->name ?? 'N/A',
                ]);

            }

            // ✅ Add Totals Row (Properly formatted with spacing)
            fputcsv($file, []); // Empty Row for Separation
            fputcsv($file, ['Ramadan Receipts', '', '', $totalReceipts]);
            fputcsv($file, ['Total Amount', '', '', sprintf('%.2f', $totalAmount)]);

            fputcsv($file, []); // Empty row for spacing
            fputcsv($file, ['Cash Amount', '', '', number_format($totalCashAmount, 2)." ($totalCashCount)"]);
            fputcsv($file, ['Online Amount', '', '', number_format($totalOnlineAmount, 2)." ($totalOnlineCount)"]);
            fputcsv($file, ['No Payment Amount', '', '', number_format($totalNotSelectedAmount, 2)." ($totalNotSelectedCount)"]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // View a collection
    public function show($id)
    {
        $collection = RamzanCollection::findOrFail($id);

        return view('ramzan.show', compact('collection'));
    }

    private function WhatsAppMessage($mobile, $pdfUrl): bool|string
    {
        $apiKey = '68400ea593ee4cc098ef960d9c5c5c47';
        $apiUrl = 'https://whatsappnew.bestsms.co.in/wapp/v2/api/send';

        $postData = [
            'apikey' => $apiKey,
            'mobile' => $mobile,
            'msg' => "आप ने तहेरीक सुन्नी दावते इस्लामी की इमदाद फरमाई हम आपका शुक्रिया अदा करते है. अल्लाह आपके इमदाद को कुबुल फरमाए और आपको बेहतर सिलाह अता फरमाए आमीन
जज़ाकल्लाहु खैर: $pdfUrl",
            //'pdf' => $pdfUrl
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return 'Curl error: '.curl_error($ch);
        }

        curl_close($ch);

        return $response;
    }

    /////Thank you page
    public function Thankyou()
    {
        $general = General::first();

        return view('ramzan.thankyou', compact('general'));
    }
}
