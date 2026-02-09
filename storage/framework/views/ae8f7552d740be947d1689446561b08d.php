
        <?php
            $payment = DB::table('payment')->first();
        ?>

        <form action="<?php echo e(route('update.payment.settings')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row">
                <!-- API Key -->
                <div class="col-md-6">
                    <label class="form-label">API Key</label>
                    <input type="text" class="form-control" name="apikey" value="<?php echo e($payment->apikey ?? ''); ?>">
                </div>

                <!-- Secret Key -->
                <div class="col-md-6">
                    <label class="form-label">Secret Key</label>
                    <input type="text" class="form-control" name="secretkey" value="<?php echo e($payment->secretkey ?? ''); ?>">
                </div>
            </div>

            <div class="row mt-3">
                <!-- Payment Option -->
                <div class="col-md-6">
                    <label class="form-label">Payment Option</label>
                    <select name="payment_option" class="form-control">
                        <?php
                            $gateways = ['paypal', 'stripe', 'paytm', 'razorpay', 'instamojo', 'paystack', 'flutterwave', 'mobilepayme'];
                        ?>
                        <?php $__currentLoopData = $gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($gateway); ?>" <?php echo e(($payment->payment_option ?? 'razorpay') == $gateway ? 'selected' : ''); ?>>
                                <?php echo e(ucfirst($gateway)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" <?php echo e(($payment->status ?? '') == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e(($payment->status ?? '') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-success mt-3">Save</button>
        </form>
  <?php /**PATH /home/sites/20a/5/57972816bd/public_html/sdi_jawhar/resources/views/settings/payment.blade.php ENDPATH**/ ?>