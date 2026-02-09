<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faizane Sadique</title>
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
        background-color: #73AD21;
        color: white;
    }

    .curveHead {
        border-radius: 10px;
        background: #73AD21;
        padding: 8px;
        color: #ffffff;
        font-weight: bold;
        text-align: center;
    }

    .green-row {
        background: #73AD21;
        color: white;
        font-weight: bold;
        text-align: center;
    }

    .left-align {
        text-align: left;
        padding-left: 10px;
    }

    .qr-container img {
        max-width: 90px;
        width: 100%;
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
            width: 50px;
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
                        <strong><?php echo e($general->title ?? ''); ?></strong><br>
                        <?php echo e($general->subtitle ?? ''); ?><br>
                        <small><?php echo e($general->address ?? ''); ?></small><br>
                        <small><?php echo e($general->contact ?? ''); ?></small>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="green-row">
            <td colspan="2" style="font-size: 10px;">
                <strong>Taqseem Wali Hisso Ki Qurbani</strong> |
                <strong>Receipt No:</strong> 2025/<?php echo e($qurbani->id); ?>

                <?php if($qurbani->receipt_book): ?> (<?php echo e($qurbani->receipt_book); ?>) <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="left-align" colspan="2">
                <strong>Name:</strong> <strong><?php echo e(ucfirst($qurbani->contact_name)); ?></strong>
            </td>
        </tr>
        <tr>
            <td class="left-align">
                <strong>Contact:</strong> <?php echo e($qurbani->mobile); ?>

                <?php if(!empty($qurbani->alternative_mobile)): ?>
                / <?php echo e($qurbani->alternative_mobile); ?>

                <?php endif; ?>
            </td>
            <td class="left-align"><strong>Date:</strong> <?php echo e($qurbani->created_at->format('d-m-Y')); ?> &nbsp; &nbsp; <strong>Day <?php echo e(ucfirst($qurbani->qurbani_days)); ?></strong>
            </td>
        </tr>
        <tr>
            <td class="left-align"><strong>Payment Mode:</strong> <?php echo e($qurbani->payment_type); ?></td>
            <?php if($qurbani->payment_type == 'Online'): ?>
            <td class="left-align"><strong>Transaction ID:</strong> <?php echo e($qurbani->transaction_number); ?></td>
            <?php else: ?>
            <td></td>
            <?php endif; ?>
        </tr>
        <tr>
            <td colspan="2">
                <table class="styled-table" style="margin-top: 1px;">
                    <thead>
                        <tr>
                            <th style="width: 10%;">No</th>
                            <th style="width: 60%;">Name</th>
                            <th style="width: 30%;">Hissa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $totalHissa = 0; ?>
                        <?php $__currentLoopData = $qurbanihisse; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $hissa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $hissaCount = 1;
                        $displayName = $hissa->name;
                        if ($hissa->aqiqah == 1) {
                            if ($hissa->gender == 'Male') {
                                $displayName .= ' (Aqiqah Male)';
                                $hissaCount = 1;
                            } elseif ($hissa->gender == 'Female') {
                                $displayName .= ' (Aqiqah Female)';
                                $hissaCount = 1;
                            }
                        }
                        $totalHissa += $hissaCount;
                        ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td class="left-align"><?php echo e($displayName); ?></td>
                            <td><?php echo e($hissaCount); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr class="green-row">
                            <td colspan="2"><strong>Total Hissa</strong></td>
                            <td><strong><?php echo e($totalHissa); ?></strong></td>
                        </tr>
                        <tr class="green-row">
                            <td colspan="2">Total Amount</td>
                            <td><?php echo e($qurbani->total_amount); ?></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

       <tr class="green-row">
    <td class="centre-align" colspan="2">
        <strong>Payment Collected By:</strong> <?php echo e($qurbani->user->name ?? 'N/A'); ?>

    </td>
</tr>

        <tr>
            <td class="qr-container">
                <img src="<?php echo e($qrPath); ?>" alt="QR Code">
            </td>
            <td class="bank-details left-align" style="font-size: 12px;">
                <?php if($general->bankdetail): ?>
                <strong>Bank Details:</strong><br>
                <small><?php echo nl2br(e($general->bankdetail)); ?></small>
                <?php endif; ?>
            </td>
        </tr>
        <!-- Footer Image -->
        <tr>
            <td colspan="2" class="image-container">
                <img src="<?php echo e($footerImgPath); ?>" alt="Footer Image" class="footer-image">
            </td>
        </tr>
        <!-- Instagram Link -->
        <tr>
            <td colspan="2" class="instagram-link">
                <a href="https://instagram.com/sdipronasik?igshid=k49z97epdxrn" target="_blank">
                Click Here to Follow Us On Instagram
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
    Download Qurbani Receipt
</a>
    <!--<a href="<?php echo e($pdfUrl); ?>" target="_blank" class="primary btn">Click here to download receipt</a>-->
</body>
</html>
<?php /**PATH /home/sites/20a/5/57972816bd/public_html/sdi_jawhar/resources/views/respdfview.blade.php ENDPATH**/ ?>