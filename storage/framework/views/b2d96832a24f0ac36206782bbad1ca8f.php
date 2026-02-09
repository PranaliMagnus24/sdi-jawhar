<?php $__env->startSection('content'); ?>
<div class="row mb-3">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Add New Qurbani</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="<?php echo e(route('qurbanis.index')); ?>">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<!-- Full Screen Loader (Center Positioned) -->
<div id="formLoader" class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white" style="opacity: 0.7; z-index: 9999;">
    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<form action="<?php echo e(route('qurbanis.store')); ?>" method="POST" enctype="multipart/form-data" onsubmit="return disableSubmitButton();">
    <?php echo csrf_field(); ?>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for=""><strong>Name: <span style="color: red;">*</span></strong></label>
                <input type="text" name="contact_name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('contact_name')); ?>" placeholder="Name">
            <?php $__errorArgs = ['contact_name'];
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
            <div class="col-md-3">
                <label for=""><strong>Mobile: <span style="color: red;">*</span></strong></label>
                <input type="text" name="mobile" maxlength="10" value="<?php echo e(old('mobile')); ?>" class="form-control <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Mobile" >
                <?php $__errorArgs = ['mobile'];
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
            <div class="col-md-3">
                <label for=""><strong>Alternative Mobile</strong></label>
                <input type="text" name="alternative_mobile" maxlength="10" value="<?php echo e(old('alternative_mobile')); ?>" class="form-control <?php $__errorArgs = ['alternative_mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Alternative Mobile" >
                <?php $__errorArgs = ['alternative_mobile'];
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
    <div class="row mb-3">
        <div class="col-md-6">
            <label for=""><strong>Receipt Book:</strong></label>
            <input type="text" name="receipt_book" class="form-control <?php $__errorArgs = ['receipt_book'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('receipt_book', isset($qurbani) ? $qurbani->receipt_book : '')); ?>" placeholder="Enter Receipt Number (Optional)">
            <?php $__errorArgs = ['receipt_book'];
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
        <div class="col-md-6">
            <label for=""><strong>Select Day:  <span style="color: red;">*</span></strong></label>
            <select name="qurbani_days" id="" class="form-select">
                <option value="">--Select Day--</option>
                

           <?php if(now()->setTimezone('Asia/Kolkata') <= '2025-06-06 23:59:59' || auth()->id() == 7  || auth()->id() == 1): ?>
            <option value="1" <?php echo e(old('qurbani_days') == '1' ? 'selected' : ''); ?>>Day 1</option>
           <?php endif; ?>
            
            <?php if(now()->setTimezone('Asia/Kolkata') <= '2025-06-07 23:59:59' || auth()->id() == 7  || auth()->id() == 1): ?>
                <option value="2" <?php echo e(old('qurbani_days') == '2' ? 'selected' : ''); ?>>Day 2</option>
            <?php endif; ?>
            
            <?php if(now()->setTimezone('Asia/Kolkata') <= '2025-06-08 23:59:59' || auth()->id() == 32 || auth()->id() == 7  || auth()->id() == 1): ?>
                <option value="3" <?php echo e(old('qurbani_days') == '3' ? 'selected' : ''); ?>>Day 3</option>
            <?php endif; ?>
            <?php if(now()->setTimezone('Asia/Kolkata') <= '2025-06-09 13:59:59' || auth()->id() == 7  || auth()->id() == 1): ?>
                <option value="III" <?php echo e(old('qurbani_days') == 'III' ? 'selected' : ''); ?>>Day III</option>
            <?php endif; ?>
            </select>
             <?php $__errorArgs = ['qurbani_days'];
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
    <div class="container mb-3">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Aqiqah</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Gender</th>
                        <!--<th class="text-center">Hissa</th>-->
                        <th class="text-center">Remove</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    <?php if(old('name')): ?>
                    <?php $__currentLoopData = old('name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isHuzur = old('huzur')[$index] ?? null;
                        ?>
                        <?php if($isHuzur): ?>
                        <tr class="rowClass huzur-row">
                            <td class="text-center">
                                <input type="hidden" name="aqiqah[]" value="">
                            </td>
                            <td class="text-center" style="width: 448px;">
                                <input type="text" name="name" class="form-control" value="HAZRAT MOHAMMAD SALLALLAHU ALAIHI WASALLAM" readonly>
                                <input type="hidden" name="hissa[]" class="form-control" value="1" readonly>
                            </td>
                            <td class="text-center">
                                <select name="gender[]" class="form-select" style="display:none;">
                                    <option value="">Select</option>
                                </select>

                            </td>
                            <td class="text-center">
                                <input type="hidden" name="huzur[]" value="1">

                                <!--<i class="fa-solid fa-trash"></i>-->
                                <a class="btn btn-danger remove" type="button"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php else: ?>
                        <tr class="rowClass">
                            <td class="text-center">
                                <input type="hidden" class="aqiqah-input" name="aqiqah[]" value="<?php echo e(old('aqiqah')[$index] ?? 0); ?>">
                                <input type="checkbox" class="aqiqah-check" <?php echo e((old('aqiqah')[$index] ?? 0) == 1 ? 'checked' : ''); ?>>
                            </td>
                            <td class="text-center">
                                <input type="text" name="name[]" class="form-control name-input" value="<?php echo e($name); ?>" placeholder="Name">
                                <input type="hidden" name="hissa[]" class="form-control hissa-input" value="<?php echo e(old('hissa')[$index] ?? 1); ?>" readonly>
                            </td>
                            <td class="text-center">
                                <select name="gender[]" class="form-select aqiqah-select" style="<?php echo e((old('aqiqah')[$index] ?? 0) == 1 ? '' : 'display:none;'); ?>">
                                    <option value="">Select</option>
                                    <option value="Male" <?php echo e(old('gender')[$index] == 'Male' ? 'selected' : ''); ?>>Male</option>
                                    <option value="Female" <?php echo e(old('gender')[$index] == 'Female' ? 'selected' : ''); ?>>Female</option>
                                </select>
                                 <?php $__errorArgs = ['gender.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                 <span class="text-danger"><?php echo e($message); ?></span>
                                 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </td>
                            <td class="text-center">
                                <input type="hidden" name="huzur[]" value="0">
                                <!--<button class="btn btn-danger remove" type="button">Remove</button>-->
                                <a class="btn btn-danger remove" type="button"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                
                <tr class="rowClass">
                    <td class="text-center">
                        <input type="hidden" class="aqiqah-input" name="aqiqah[]" value="0">
                        <input type="checkbox" class="aqiqah-check">
                    </td>
                    <!--<td class="text-center">-->
                    <!--    <input type="text" name="name[]" class="form-control name-input" placeholder="Name">-->
                    <!--</td>-->
                    <td class="text-center" style="min-width: 150px;">
                        <input type="text" name="name[]" class="form-control w-100 name-input" placeholder="Name">
                        <input type="hidden" name="hissa[]" class="form-control hissa-input" value="1" readonly>
                    </td>
                    <td class="text-center">
                        <select name="gender[]" class="form-select aqiqah-select" style="display:none;">
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <input type="hidden" name="huzur[]" value="0">
                        <!--<button class="btn btn-danger remove" type="button">Remove</button>-->
                        <a class="btn btn-danger remove" type="button"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <button class="btn btn-md btn-primary" id="addBtn" type="button">Add New Row</button>
    <button class="btn btn-md btn-primary" id="addBtnHuzur" type="button">Add Huzur Name</button>
</div>
<div class="row mb-3">
    <div class="col-md-2">
        <label for="" class="form-lable"><strong>Total Amount</strong></label>
        <input type="text" class="form-control" name="total_amount" id="txtamount" readonly>
    </div>
    <div class="col-md-2">
        <strong>Payment Method:<span style="color: red;">*</span></strong>
        <select name="payment_type" id="payment_method" class="form-select" onchange="togglePaymentDetails(this);">
            <option value="">Payment Method</option>
            <option value="Cash" <?php echo e(old('payment_type') == 'Cash' ? 'selected' : ''); ?>>Cash</option>
            <option value="RazorPay" <?php echo e(old('payment_type') == 'RazorPay' ? 'selected' : ''); ?>>Online</option>
        </select>
        <?php $__errorArgs = ['payment_type'];
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
    <div class="col-md-3" id="razorpay-details" style="display: none;">
        <label for="transaction_number"><strong>Transaction ID:<span style="color: red;">*</span></strong></label>
        <input type="text" name="transaction_number" id="transaction_number" class="form-control <?php $__errorArgs = ['transaction_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Enter Transaction ID">
        <?php $__errorArgs = ['transaction_number'];
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
<div class="col-md-12 text-center">
    <button type="submit" class="btn btn-success btn-sm" id="saveButton">
        <span id="buttonText"><i class="fa-solid fa-floppy-disk"></i> Submit</span>
        <span id="buttonLoader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
    </button>
</div>
</div>
</form>
<?php $__env->stopSection(); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
//     $(document).ready(function () {
//     const now = new Date();
//     const currentHour = now.getHours();
//     const currentMinutes = now.getMinutes();

//     if (currentHour > 23 || (currentHour === 23 && currentMinutes > 59)) {
//         $('#qurbani_days option[value="1"]').remove();
//     }
// });



    const autosuggestUrl = '<?php echo e(route('qurbani.autosuggest')); ?>';
    ////Loader logic
    function disableSubmitButton() {
        var saveButton = document.getElementById("saveButton");
        var formLoader = document.getElementById("formLoader");
        if (saveButton.disabled) {
            return false;
        }
        saveButton.disabled = true;
        formLoader.classList.remove("d-none");
        return true;
    }
</script>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sites/20a/5/57972816bd/public_html/sdi_jawhar/resources/views/qurbanis/create.blade.php ENDPATH**/ ?>