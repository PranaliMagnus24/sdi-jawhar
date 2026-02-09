

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2 class="mb-4">Master Settings</h2>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs" id="settingsTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general"
                    type="button" role="tab">General</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="whatsapp-tab" data-bs-toggle="tab" data-bs-target="#whatsapp"
                    type="button" role="tab">Whatsapp</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="sms-tab" data-bs-toggle="tab" data-bs-target="#sms"
                    type="button" role="tab">SMS</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment"
                    type="button" role="tab">Payment</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3" id="settingsTabContent">
        <div class="tab-pane fade show active" id="general" role="tabpanel">
            <?php echo $__env->make('settings.general', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="tab-pane fade" id="whatsapp" role="tabpanel">
            <?php echo $__env->make('settings.whatsapp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="tab-pane fade" id="sms" role="tabpanel">
            <?php echo $__env->make('settings.sms', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="tab-pane fade" id="payment" role="tabpanel">
            <?php echo $__env->make('settings.payment', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sites/20a/5/57972816bd/public_html/sdi_jawhar/resources/views/settings/master.blade.php ENDPATH**/ ?>