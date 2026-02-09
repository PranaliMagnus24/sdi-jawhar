<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New Collection</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="<?php echo e(route('collectionlist')); ?>">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success text-center">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <!-- Full Screen Loader (Center Positioned) -->
    <div id="formLoader"
        class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white"
        style="opacity: 0.7; z-index: 9999;">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Inline onsubmit attribute added to prevent multiple submissions -->
    <form action="<?php echo e(route('collection.store')); ?>" method="POST" onsubmit="return disableSubmitButton();">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="user_id" value="<?php echo e(auth()->id()); ?>">

        <div class="row">

            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <strong>Name:<span style="color: red;">*</span></strong>
                    <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        value="<?php echo e(old('name')); ?>" placeholder="Name">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Contact Field -->
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <strong>Contact:<span style="color: red;">*</span></strong>
                    <input type="text" name="contact" class="form-control <?php $__errorArgs = ['contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        value="<?php echo e(old('contact')); ?>" placeholder="Contact">
                    <?php $__errorArgs = ['contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Donation Category Field -->
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <strong>Donation Category:<span style="color: red;">*</span></strong>
                    <select name="donationcategory" class="form-control <?php $__errorArgs = ['donationcategory'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">Select Category</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->name); ?>" <?php echo e(old('donationcategory') == $category->name ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['donationcategory'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Amount Field -->
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <strong>Amount:<span style="color: red;">*</span></strong>
                    <input type="text" name="amount" class="form-control <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        value="<?php echo e(old('amount')); ?>">
                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Payment Mode Field -->
            <div class="col-md-6">
                <strong>Payment:<span style="color: red;">*</span></strong>
                        <select name="payment_mode" id="payment_mode" class="form-control <?php $__errorArgs = ['payment_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    onChange="toggleCollectionPaymentDetails(this);">
                    <option value="">Payment Method</option>
                    <option value="Cash" <?php echo e(old('payment_mode') == 'Cash' ? 'selected' : ''); ?>>Cash</option>
                    <option value="Online" <?php echo e(old('payment_mode') == 'Online' ? 'selected' : ''); ?>>Online</option>
                    <option value="Unpaid" <?php echo e(old('payment_mode') == 'Unpaid' ? 'selected' : ''); ?>>Unpaid</option>
                </select>
                <?php $__errorArgs = ['payment_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Transaction ID Field (Initially Hidden) -->
            <div class="col-md-6 mb-3" id="transactionField" style="display: none;">
                <strong>Transaction ID:<span style="color: red;">*</span></strong>
                <input type="text" name="transaction_id" id="transaction_id"
                    class="form-control <?php $__errorArgs = ['transaction_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('transaction_id')); ?>"
                    placeholder="Enter Transaction ID">
                <?php $__errorArgs = ['transaction_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Date Field -->
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <strong>Date:</strong>
                    <input type="datetime-local" name="date" class="form-control" id="date" value="<?php echo e(old('date')); ?>">
                </div>
            </div>

            <!-- Address Field -->
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <strong>Address:</strong>
                    <input type="text" name="address" class="form-control" value="<?php echo e(old('address')); ?>"
                        placeholder="Address">
                </div>
            </div>

            <!-- Receipt Book Field -->
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <strong>Receipt Book:</strong>
                    <input type="text" name="receipt_book" id="receipt_book" class="form-control"
                        value="<?php echo e(old('receipt_book')); ?>" placeholder="Enter Receipt Number (Optional)">
                </div>
            </div>

            <!-- Note Field -->
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <strong>Note:</strong>
                    <input type="text" name="note" class="form-control" value="<?php echo e(old('note')); ?>" placeholder="Note">
                </div>
            </div>

            <!-- Submit Button with Loader Icons -->
            <div class="col-md-12 text-center mt-3">
                <button type="submit" class="btn btn-success btn-sm" id="saveButton">
                    <span id="buttonText"><i class="fa-solid fa-floppy-disk"></i> Save</span>
                    <span id="buttonLoader" class="spinner-border spinner-border-sm d-none" role="status"
                        aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </form>

    <script>
        // Set default date if not provided and set transaction field visibility on page load
        window.onload = function () {
            const dateInput = document.getElementById("date");
            if (dateInput && !dateInput.value) {
                const currentDate = new Date();
                let year = currentDate.getFullYear();
                let month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
                let day = currentDate.getDate().toString().padStart(2, '0');
                let hours = currentDate.getHours().toString().padStart(2, '0');
                let minutes = currentDate.getMinutes().toString().padStart(2, '0');
                dateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
            }
            toggleCollectionPaymentDetails(document.getElementById("payment_mode"));
        };

    // Function to show/hide transaction field based on payment method
    function toggleCollectionPaymentDetails(select) {
        const transactionField = document.getElementById("transactionField");
        const transactionInput = document.getElementById("transaction_id");
        if (select && select.value === "Online") {
            transactionField.style.display = "block";
            transactionInput.setAttribute("required", "required");
        } else {
            transactionField.style.display = "none";
            transactionInput.removeAttribute("required");
        }
    }

        // Function to disable submit button and show loader; returns false if already submitted
        function disableSubmitButton() {
            var saveButton = document.getElementById("saveButton");
            var formLoader = document.getElementById("formLoader");
            // If the button is already disabled, prevent submission
            if (saveButton.disabled) {
                return false;
            }
            // Disable the submit button immediately
            saveButton.disabled = true;
            // Show the full-screen loader
            formLoader.classList.remove("d-none");
            return true;
        }
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\sdi_jawhar\resources\views/ramzan/collection.blade.php ENDPATH**/ ?>