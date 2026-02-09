<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $general->title ?? '' }} - {{ $general->subtitle ?? '' }}</title>

    <style>
        @page {
            size: 60mm auto;
            margin: 10mm;
        }

        @font-face {
            font-family: "Noto Sans Devanagari";
            src: url("{{ storage_path('fonts/NotoSansDevanagari-Regular.ttf') }}") format("truetype");
        }

        body {
            font-family: "Noto Sans Devanagari", sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .styled-table {
            border: 1px solid #000;
            text-align: center;
        }

        .styled-table td,
        .styled-table th {
            border: 1px solid #000;
            padding: 6px;
            word-wrap: break-word;
        }

        .curveHead {
            background: #1a6753;
            color: #fff;
            font-weight: bold;
            font-size: 14px;
        }

        .green-row {
            background: #1a6753;
            color: #fff;
            font-weight: bold;
        }

        .left-align {
            text-align: left;
            padding-left: 8px;
        }

        .logo-container img {
            width: 60px;
        }

        .qr-container img {
            width: 160px;
        }

        .footer-image {
            width: 60%;
        }

        .instagram-link a {
            font-size: 10px;
            font-weight: bold;
            color: blue;
            text-decoration: none;
        }
    </style>
</head>

<body>

<table class="styled-table">

    <!-- Logo -->
    <!--<tr>-->
    <!--    <td colspan="2" class="logo-container">-->
    <!--        <img src="{{ $logoPath }}">-->
    <!--    </td>-->
    <!--</tr>-->

    <!-- Header -->
    <!--<tr>-->
    <!--    <td colspan="2" class="curveHead">{{ $general->title ?? '' }}</td>-->
    <!--</tr>-->

    <!--<tr>-->
    <!--    <td colspan="2" style="font-size:14px;font-weight:bold;">-->
    <!--        {{ $general->subtitle ?? '' }}-->
    <!--    </td>-->
    <!--</tr>-->

    <!--<tr>-->
    <!--    <td colspan="2"><small>{{ $general->address ?? '' }}</small></td>-->
    <!--</tr>-->

    <!--<tr>-->
    <!--    <td colspan="2"><small>{{ $general->contact ?? '' }}</small></td>-->
    <!--</tr>-->
    
    <tr>
            <td colspan="2" style="padding: 10px;">
                <div style="display: flex; align-items: center; justify-content: center; position: relative;">
                    <!-- Logo (absolute left) -->
                    <div style="position: absolute; left: 0;">
                        <img src="{{ $logoPath }}" alt="Logo" style="height: 50px; width: 50px;">
                    </div>
                    <!-- Centered Content -->
                    <div style="text-align: center; width: 100%;">
                        <strong >{{ $general->title ?? '' }}</strong><br>
                        <span style="font-size: 16px;">{{ $general->subtitle ?? '' }}</span><br>
                        <span>{{ $general->address ?? '' }}</span><br>
                        <span>{{ $general->contact ?? '' }}</span>
                    </div>
                </div>
            </td>
        </tr>

    <!-- Collection -->
    <tr class="green-row">
        <td colspan="2">
            Ramadan Collection | Receipt No: {{ date('Y') }}/{{ $collection->id }}
            @if($collection->receipt_book)
                ({{ $collection->receipt_book }})
            @endif
        </td>
    </tr>

    <tr>
        <td colspan="2" class="left-align" style="font-size:14px;">
            <strong>Name:</strong> {{ ucfirst($collection->name) }}
        </td>
    </tr>

    <tr>
        <td class="left-align"><strong>Contact:</strong> {{ $collection->contact }}</td>
        <td class="left-align"><strong>Date:</strong> {{ $collection->date }}</td>
    </tr>

    <tr>
        <td class="left-align"><strong>Donation Category:</strong> {{ $collection->donationcategory }}</td>
        <td class="left-align"><strong>Amount:</strong> Rs. {{ $collection->amount }}</td>
    </tr>

    <tr>
        <td class="left-align"><strong>Payment Mode:</strong> {{ $collection->payment_mode }}</td>
        <td class="left-align">
            @if($collection->payment_mode === 'Online')
                <strong>Txn ID:</strong> {{ $collection->transaction_id }}
            @endif
        </td>
    </tr>

    <tr class="green-row">
        <td colspan="2">Payment Collected By: {{ Auth::user()->name ?? 'N/A' }}</td>
    </tr>

    <tr>
        <td class="qr-container">
            <strong>Scan to Pay</strong><br>
            <img src="{{ $qrPath }}">
        </td>
        <td class="left-align">
            @if($general->bankdetail)
                <strong>BANK DETAILS:</strong><br>
                {!! nl2br(e(strtoupper($general->bankdetail))) !!}
            @endif
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <img src="{{ $dailyPattiPath }}" class="footer-image">
        </td>
    </tr>

    <tr>
        <td colspan="2" class="instagram-link">
            <a href="https://youtube.com/@madarsaanwarerazajawhar5477">
                Subscribe our YouTube Channel
            </a>
        </td>
    </tr>

    @if($general->note)
        <tr class="green-row">
            <td colspan="2">{{ $general->note }}</td>
        </tr>
    @endif

    @if($general->footer)
        <tr class="green-row">
            <td colspan="2">{{ $general->footer }}</td>
        </tr>
    @endif

</table>

</body>
</html>
