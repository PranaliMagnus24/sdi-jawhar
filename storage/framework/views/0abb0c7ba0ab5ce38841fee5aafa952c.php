<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo e($general->title ?? ''); ?> - <?php echo e($general->subtitle ?? ''); ?></title>
    <style>
    @font-face {
        font-family: "Noto Sans Devanagari";
        src: url("<?php echo e(public_path('storage/fonts/NotoSansDevanagari-Regular.ttf')); ?>") format("truetype");
    }

    body {
        font-family: "Noto Sans Devanagari", sans-serif;
        margin: 0;
        padding: 10px;
        font-size: 12px;
    }

    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: white;
        opacity: 0.5;
        z-index: -1;
    }

    .styled-table {
        width: 100%;
        border-collapse: collapse;
        margin: 5px auto;
        font-size: 0.9em;
        border: 1px solid #000;
        background: rgba(255, 255, 255, 0.85);
        border-radius: 10px;
        text-align: center;
    }

    .styled-table td, .styled-table th {
        padding: 5px;
        border: 1px solid #000;
        word-wrap: break-word;
    }

    .styled-table th {
        background-color: #1a6753;
        color: white;
    }

    .curveHead {
        border-radius: 10px;
        background: #1a6753;
        padding: 8px;
        color: #ffffff;
        font-weight: bold;
        text-align: center;
    }

    .green-row {
        background: #1a6753;
        color: white;
        font-weight: bold;
        text-align: center;
    }

    .left-align {
        text-align: left;
        padding-left: 10px;
    }

    .qr-container img {
        /*max-width: 110px;
        width: 100%;*/
        height: auto;
        display: block;
        margin: 0 auto;
        object-fit: contain;
    }

    .image-container {
        text-align: center;
        margin-top: 5px;
    }

    .footer-image {
        max-width: 100%;
        height: auto;
        object-fit: contain;
        display: block;
        margin: 0 auto;
    }

    .logo-container img {
        width: 60px;
        display: block;
        margin: 0 auto;
    }

    .instagram-link a {
        font-size: 12px;
        font-weight: bold;
        color: blue;
        text-decoration: none;
        display: block;
        text-align: center;
        margin-top: 5px;
    }

    .btn-download {
    display: inline-block;
    padding: 10px 15px;
    background-color: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
}

.btn-download:hover {
    background-color: #218838;
}

    /* Responsive tweaks for smaller screens */
    @media screen and (max-width: 600px) {
        body {
            font-size: 10px;
        }

        .styled-table th, .styled-table td {
            font-size: 10px;
            padding: 4px;
        }

        .footer-image {
            height: auto;
        }

        .logo-container img,
        .qr-container img {
            width: 140px;
        }

        .instagram-link a {
            font-size: 10px;
        }
    }
    </style>
</head>
<body>
    <table class="styled-table" style="border: 1px solid black;">
        <!----------Heade logo------->
        <tr>
            <td colspan="2" style="padding: 10px;">
                <div style="display: flex; align-items: center; justify-content: center; position: relative;">
                    <!-- Logo (absolute left) -->
                    <div style="position: absolute; left: 0;">
                        <img src="<?php echo e($logoPath); ?>" alt="Logo" style="height: 50px; width: 50px;">
                    </div>
                    <!-- Centered Content -->
                    <div style="text-align: center; width: 100%;">
                        <strong ><?php echo e($general->title ?? ''); ?></strong><br>
                        <span style="font-size: 16px;"><?php echo e($general->subtitle ?? ''); ?></span><br>
                        <span><?php echo e($general->address ?? ''); ?></span><br>
                        <span><?php echo e($general->contact ?? ''); ?></span>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="green-row">
            <td colspan="2" style="font-size: 10px;">
                <strong>Ramadan Collection</strong> | <strong>Receipt No:</strong> <?php echo e(date('Y')); ?>/<?php echo e($collection->id); ?>

                <?php if($collection->receipt_book): ?>
                    (<?php echo e($collection->receipt_book); ?>)
                <?php endif; ?>
            </td>
        </tr>
        <tr>
<td colspan="2" class="left-align" style="font-size: 16px;">
<strong>Name:</strong> <strong><?php echo e(ucfirst($collection->name)); ?></strong>

    </td>
</tr>


        </tr>
        <tr>
            <td class="left-align"><strong>Contact:</strong> <?php echo e($collection->contact); ?></td>
            <td class="left-align"><strong>Date:</strong> <?php echo e($collection->date); ?></td>
        </tr>
        <tr>
            <td class="left-align"><strong>Donation Category:</strong> <?php echo e($collection->donationcategory); ?></td>
            <td class="left-align"><strong>Amount:</strong> Rs.<?php echo e($collection->amount); ?></td>
        </tr>
        <tr>
    <td class="left-align"><strong>Payment Mode:</strong> <?php echo e($collection->payment_mode); ?></td>

    <?php if($collection->payment_mode == 'Online'): ?>
        <td class="left-align"><strong>Transaction ID:</strong> <?php echo e($collection->transaction_id); ?></td>
    <?php else: ?>
        <td></td>
    <?php endif; ?>
</tr>
        

       <tr class="green-row">
    <td class="centre-align" colspan="2">
        <strong>Payment Collected By:</strong> <?php echo e(Auth::user()->name ?? 'N/A'); ?>

    </td>
</tr>

        <tr>
            <td class="qr-container">
                <img src="<?php echo e($qrPath); ?>" alt="QR Code">
            </td>
            <td class="bank-details left-align" style="font-size: 12px;">
                <?php if($general->bankdetail): ?>
                <strong>Bank Details:</strong><br>
                <small><?php echo nl2br(e(strtoupper($general->bankdetail))); ?></small>
                <?php endif; ?>
            </td>
        </tr>
        <!-- Footer Image -->
        <tr>
            <td colspan="2" class="image-container">
                <img src="<?php echo e($dailyPattiPath); ?>" alt="Footer Image" class="footer-image">
            </td>
        </tr>
        <!-- Instagram Link -->
         <tr>
            <td colspan="2" class="instagram-link">
                <a href="https://youtube.com/@madarsaanwarerazajawhar5477?si=t5gYI557wy5Zv17L" target="_blank">
                Click Here to subscribe our Youtube Channel
            </a>
            </td>
        </tr>
        <!-- Notes -->
        <?php if($general->note): ?>
        <tr class="green-row">
            <td colspan="2">
                <?php echo e($general->note); ?>

            </td>
        </tr>
        <?php endif; ?>
        <?php if($general->footer): ?>
        <tr class="green-row">
            <td colspan="2"><?php echo e($general->footer); ?></td>
        </tr>
        <?php endif; ?>
    </table>
    <br />
    <a href="<?php echo e($pdfUrl); ?>" class="btn-download" target="_blank">
    Download Receipt
</a>
    <!--<a href="<?php echo e($pdfUrl); ?>" target="_blank" class="primary btn">Click here to download receipt</a>-->
</body>
</html>
<?php /**PATH D:\laragon\www\sdi_jawhar\resources\views/ramzan/view.blade.php ENDPATH**/ ?>