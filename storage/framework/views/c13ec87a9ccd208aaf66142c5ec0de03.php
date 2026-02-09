

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2 class="mb-4">Update General Settings</h2>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php
        $general = DB::table('general')->where('ID', 1)->first();
    ?>

    <form action="<?php echo e(route('update.general.settings')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <!-- Row 1: Logo & Favicon -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Logo</label>
                <input type="file" class="form-control" name="logo">
                <?php if($general->logo): ?>
                    <img src="<?php echo e(asset('general/' . $general->logo)); ?>" alt="Current Logo" width="100" class="mt-2">
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Favicon</label>
                <input type="file" class="form-control" name="favicon">
                <?php if($general->favicon): ?>
                    <img src="<?php echo e(asset('general/' . $general->favicon)); ?>" alt="Current Favicon" width="50" class="mt-2">
                <?php endif; ?>
            </div>
        </div>

        <!-- Row 2: Email & Address -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo e($general->email); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address"><?php echo e($general->address); ?></textarea>
            </div>
        </div>

        <!-- Row 3: State & City -->
        <?php
            use App\Models\State;
            $maharashtra = State::where('name', 'Maharashtra')->first();
            $cities = $maharashtra ? $maharashtra->cities : collect();
        ?>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">State</label>
                <input type="text" class="form-control" name="state" value="Maharashtra" readonly>
                <input type="hidden" name="state_id" value="<?php echo e($maharashtra->id); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">City</label>
                <select name="city" class="form-control" id="city">
                    <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($city->name); ?>" <?php echo e($general->city == $city->name ? 'selected' : ''); ?>>
                            <?php echo e($city->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
                <label class="form-label">Heading</label>
                <input type="text" class="form-control" name="heading" value="<?php echo e($general->heading); ?>">
            </div>
        <!-- Row 4: Title & Subtitle -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" name="title" value="<?php echo e($general->title); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Subtitle</label>
                <input type="text" class="form-control" name="subtitle" value="<?php echo e($general->subtitle); ?>">
            </div>
        </div>

        <!-- Row 5: Contact & Bank Detail -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Contact</label>
                <input type="text" class="form-control" name="contact" value="<?php echo e($general->contact); ?>">
            </div>
            <div class="col-md-6">
    <label class="form-label">Bank Detail</label>
    <textarea class="form-control" name="bankdetail"><?php echo e($general->bankdetail); ?></textarea>
</div>

        </div>

        <!-- Row 6: Upload QR Code & Footer Logo -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Upload QR Code</label>
                <input type="file" class="form-control" name="uploadqrcode">
                <?php if($general->uploadqrcode): ?>
                    <img src="<?php echo e(asset('general/' . $general->uploadqrcode)); ?>" alt="QR Code" width="100" class="mt-2">
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Footer Logo</label>
                <input type="file" class="form-control" name="footerlogo">
                <?php if($general->footerlogo): ?>
                    <img src="<?php echo e(asset('general/' . $general->footerlogo)); ?>" alt="Footer Logo" width="100" class="mt-2">
                <?php endif; ?>
            </div>
        </div>

        <!-- Row 7: Link & Note -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Link</label>
                <input type="text" class="form-control" name="link" value="<?php echo e($general->link); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Note</label>
                <input type="text" class="form-control" name="note" value="<?php echo e($general->note); ?>">
            </div>
        </div>

        <!-- Row 8: Footer & Trust Register Number -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Footer</label>
                <input type="text" class="form-control" name="footer" value="<?php echo e($general->footer); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Trust Register Number</label>
                <input type="text" class="form-control" name="trust_register_number" value="<?php echo e($general->trust_register_number); ?>">
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-3">Save</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stateId = document.querySelector('input[name="state_id"]').value;
        const savedCity = "<?php echo e($general->city); ?>"; // Get the saved city from backend

        fetch(`/get-cities/${stateId}`)
            .then(res => res.json())
            .then(data => {
                const citySelect = document.getElementById('city');
                citySelect.innerHTML = '';

                Object.entries(data).forEach(([id, name]) => {
                    const option = new Option(name, name);
                    if (name === savedCity) {
                        option.selected = true;
                    }
                    citySelect.appendChild(option);
                });
            });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sites/20a/5/57972816bd/public_html/sdi_jawhar/resources/views/settings/general.blade.php ENDPATH**/ ?>