<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo e($general->title ?? ''); ?> - <?php echo e($general->subtitle ?? ''); ?></title>

    <style>
        @page {
            size: 60mm auto;
            margin: 10mm;
        }

        @font-face {
            font-family: "Noto Sans Devanagari";
            src: url("<?php echo e(storage_path('fonts/NotoSansDevanagari-Regular.ttf')); ?>") format("truetype");
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
    <!--        <img src="<?php echo e($logoPath); ?>">-->
    <!--    </td>-->
    <!--</tr>-->

    <!-- Header -->
    <!--<tr>-->
    <!--    <td colspan="2" class="curveHead"><?php echo e($general->title ?? ''); ?></td>-->
    <!--</tr>-->

    <!--<tr>-->
    <!--    <td colspan="2" style="font-size:14px;font-weight:bold;">-->
    <!--        <?php echo e($general->subtitle ?? ''); ?>-->
    <!--    </td>-->
    <!--</tr>-->

    <!--<tr>-->
    <!--    <td colspan="2"><small><?php echo e($general->address ?? ''); ?></small></td>-->
    <!--</tr>-->

    <!--<tr>-->
    <!--    <td colspan="2"><small><?php echo e($general->contact ?? ''); ?></small></td>-->
    <!--</tr>-->
    
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

    <!-- Collection -->
    <tr class="green-row">
        <td colspan="2">
            Ramadan Collection | Receipt No: <?php echo e(date('Y')); ?>/<?php echo e($collection->id); ?>

            <?php if($collection->receipt_book): ?>
                (<?php echo e($collection->receipt_book); ?>)
            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <td colspan="2" class="left-align" style="font-size:14px;">
            <strong>Name:</strong> <?php echo e(ucfirst($collection->name)); ?>

        </td>
    </tr>

    <tr>
        <td class="left-align"><strong>Contact:</strong> <?php echo e($collection->contact); ?></td>
        <td class="left-align"><strong>Date:</strong> <?php echo e($collection->date); ?></td>
    </tr>

    <tr>
        <td class="left-align"><strong>Donation Category:</strong> <?php echo e($collection->donationcategory); ?></td>
        <td class="left-align"><strong>Amount:</strong> Rs. <?php echo e($collection->amount); ?></td>
    </tr>

    <tr>
        <td class="left-align"><strong>Payment Mode:</strong> <?php echo e($collection->payment_mode); ?></td>
        <td class="left-align">
            <?php if($collection->payment_mode === 'Online'): ?>
                <strong>Txn ID:</strong> <?php echo e($collection->transaction_id); ?>

            <?php endif; ?>
        </td>
    </tr>

    <tr class="green-row">
        <td colspan="2">Payment Collected By: <?php echo e(Auth::user()->name ?? 'N/A'); ?></td>
    </tr>

    <tr>
        <td class="qr-container">
            <strong>Scan to Pay</strong><br>
            <img src="<?php echo e($qrPath); ?>">
        </td>
        <td class="left-align">
            <?php if($general->bankdetail): ?>
                <strong>BANK DETAILS:</strong><br>
                <?php echo nl2br(e(strtoupper($general->bankdetail))); ?>

            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <img src="<?php echo e($dailyPattiPath); ?>" class="footer-image">
        </td>
    </tr>

    <tr>
        <td colspan="2" class="instagram-link">
            <a href="https://youtube.com/@madarsaanwarerazajawhar5477">
                Subscribe our YouTube Channel
            </a>
        </td>
    </tr>

    <?php if($general->note): ?>
        <tr class="green-row">
            <td colspan="2"><?php echo e($general->note); ?></td>
        </tr>
    <?php endif; ?>

    <?php if($general->footer): ?>
        <tr class="green-row">
            <td colspan="2"><?php echo e($general->footer); ?></td>
        </tr>
    <?php endif; ?>

</table>

</body>
</html>
<?php /**PATH /home/sites/20a/5/57972816bd/public_html/sdi_jawhar/resources/views/ramzan/pdf.blade.php ENDPATH**/ ?>