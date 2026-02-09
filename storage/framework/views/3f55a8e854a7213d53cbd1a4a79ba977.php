

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <!-- Total Ramadan Receipts -->
        <div class="col-md-2">
            <div class="card">
                <div class="card-header"><?php echo e(__('Ramadan Receipts')); ?></div>
                <div class="card-body">
                    <?php echo e($ramadanReceiptCount); ?>

                </div>
            </div>
        </div>
        
        <!-- Cash Collection -->
        <div class="col-md-2">
            <div class="card">
                <div class="card-header"><?php echo e(__('Cash Collection')); ?></div>
                <div class="card-body">
                    Rs. <?php echo e(number_format($cashAmount, 2)); ?> (<?php echo e($cashReceipts); ?>)
                </div>
            </div>
        </div>

        <!-- Online Collection -->
        <div class="col-md-2">
            <div class="card">
                <div class="card-header"><?php echo e(__('Online Collection')); ?></div>
                <div class="card-body">
                    Rs. <?php echo e(number_format($onlineAmount, 2)); ?> (<?php echo e($onlineReceipts); ?>)
                </div>
            </div>
        </div>

        <!-- Unselected Payment Collection -->
        <div class="col-md-2">
            <div class="card">
                <div class="card-header"><?php echo e(__('Payment Mode(Not selected)')); ?></div>
                <div class="card-body">
                    Rs. <?php echo e(number_format($unselectedAmount, 2)); ?> (<?php echo e($unselectedReceipts); ?>)
                </div>
            </div>
        </div>

        <!-- Ramadan Collection (Cash + Online + Unselected) -->
        <div class="col-md-2">
            <div class="card">
                <div class="card-header"><?php echo e(__('Ramadan Collection')); ?></div>
                <div class="card-body">
                    Rs. <?php echo e(number_format($totalAmount, 2)); ?>

                </div>
            </div>
        </div>
    </div>

    <br>


    <?php if(Auth::user()->roles[0]->name === 'Admin' && $users->isNotEmpty()): ?>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header"><?php echo e(__('Ramadan Collection User List')); ?></div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>User Name</th>
                                <th>Receipts</th>
                                <th>Cash Amount </th>
                                <th>Online Amount </th>
                                <th>Payment Mode(Not selected) </th>
                                <th>Total Amount</th>
                            </tr>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($user->name); ?></td>
                                    <td><?php echo e($user->receipt_count); ?></td>
                                    <td>Rs. <?php echo e(number_format($user->cash_amount, 2)); ?> (<?php echo e($user->cash_receipts); ?>)</td>
                                    <td>Rs. <?php echo e(number_format($user->online_amount, 2)); ?> (<?php echo e($user->online_receipts); ?>)</td>
                                    <td>Rs. <?php echo e(number_format($user->unselected_amount, 2)); ?> (<?php echo e($user->unselected_receipts); ?>)</td>
                                    <td><strong>Rs. <?php echo e(number_format($user->total_amount, 2)); ?></strong></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\sdi_jawhar\resources\views/home.blade.php ENDPATH**/ ?>