<?php $__env->startSection('content'); ?>
<div class="row mb-3">
    <div class="col-md-6">
        <h2>Qurbani Collection List</h2>
    </div>
    <div class="col-md-6 text-md-end">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('qurbani-create')): ?>
        <a class="btn btn-success btn-sm" href="<?php echo e(route('qurbanis.create')); ?>">
            <i class="fa fa-plus"></i>
        </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view export')): ?>
          <a href="<?php echo e(route('qurbani.export')); ?>" class="btn btn-success btn-sm">
            <i class="fas fa-share-square"></i>
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Filter Form -->
<form method="GET" action="<?php echo e(route('qurbanis.index')); ?>">
    <div class="row mb-4">
        <table class="table table-bordered">
            <tr>
                <td>
                    <label for="contact_name">Name:</label>
                    <input type="text" id="contact_name" name="contact_name" value="<?php echo e(request('contact_name')); ?>" class="form-control" placeholder="Name">
                </td>
                <td>
                    <label for="mobile">Mobile:</label>
                    <input type="number" id="mobile" name="mobile" value="<?php echo e(request('mobile')); ?>" class="form-control" placeholder="Mobile">
                </td>
                <td>
                    <label for="receipt_book">Receipt No:</label>
                    <input type="text" id="receipt_book" name="receipt_book" value="<?php echo e(request('receipt_book')); ?>" class="form-control" placeholder="Receipt Number">
                </td>
                <td>
                        <label for="collected_by">Collected By:</label>
                        <select name="collected_by" class="form-select">
                            <option value="">Collected By</option>
                            <?php $__currentLoopData = $collectedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" <?php echo e(request('collected_by') == $user->id ? 'selected' : ''); ?>>
                                    <?php echo e($user->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </td>
                    <td>
    <label for="year">Year:</label>
    <select name="year" class="form-control">
        <option value="2025" <?php echo e(request('year', 2025) == 2025 ? 'selected' : ''); ?>>2025</option>
        <option value="2024" <?php echo e(request('year') == 2024 ? 'selected' : ''); ?>>2024</option>
    </select>
</td>

                <td>
                    <button type="submit" class="btn btn-primary mt-4">Filter</button>
                    <a href="<?php echo e(url('qurbanis')); ?>" class="btn btn-secondary mt-4 ms-2">Reset</a>
                </td>
            </tr>
        </table>
    </div>
</form>




<?php
    // function sortLink($label, $field) {
    //     $currentSort = request('sort_by');
    //     $currentOrder = request('order', 'desc');
    //     $isCurrent = $currentSort == $field;
    //     $newOrder = ($isCurrent && $currentOrder == 'asc') ? 'desc' : 'asc';
    //     $icon = $isCurrent ? ($currentOrder == 'asc' ? '▲' : '▼') : '⇅';

    //     $query = request()->except('sort_by', 'order');
    //     $query['sort_by'] = $field;
    //     $query['order'] = $newOrder;
    //     $url = request()->url() . '?' . http_build_query($query);

    //     return "<a href='{$url}' style='color: inherit; text-decoration: none; font-weight: bold;'>{$label} <span>{$icon}</span></a>";
    // }
?>


<table class="table table-bordered nowrap">
    <tr>
        <th>No</th>
        <th>Name</th>
        <th>Day</th>
        <th>Mobile</th>
        <th>Hissa</th>
         <?php if($year != 2024): ?>
        <th width="280px" class="nowrap">Action</th>
        <?php endif; ?>
    </tr>

    <?php $__currentLoopData = $qurbanis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qurbani): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e(++$i); ?></td>
        <td><?php echo e($qurbani->contact_name); ?></td>
        <td><?php echo e($qurbani->qurbani_days); ?></td>
        
        <td>
            <a href="tel:<?php echo e($qurbani->mobile); ?>" class="btn btn-sm btn-outline-success">
            <i class="fa fa-phone"></i> <?php echo e($qurbani->mobile); ?>

            </a>
        </td>

       <td><?php echo e($year == 2025 ? $qurbani->details->sum('hissa') : $qurbani->details2024->sum('hissa')); ?></td>

        <?php if($year != 2024): ?>
        <td>
            <form action="<?php echo e(route('qurbanis.destroy',$qurbani->id)); ?>" method="POST">
                <!--<a class="btn btn-warning btn-sm" href="/generate-pdf/<?php echo e(base64_encode($qurbani->id)); ?>" target="_blank">-->
                <!--    <i class="fa-regular fa-file-pdf"></i>-->
                <!--</a>-->
                <a class="btn btn-warning btn-sm" href="/qurbani-pdf-url/<?php echo e(base64_encode($qurbani->id)); ?>" target="_blank">
                    <i class="fa-regular fa-file-pdf"></i>
                </a>
                
                <a class="btn btn-info btn-sm" href="<?php echo e(route('qurbanis.show',$qurbani->id)); ?>">
                    <i class="fas fa-eye"></i>
                </a>
    <?php if(!in_array($qurbani->qurbani_days,[1,2,3,'III',NULL]) || auth()->id() == 7  || auth()->id() == 1): ?>

                <a class="btn btn-primary btn-sm" href="<?php echo e(route('qurbani.edit', $qurbani->id)); ?>"><i class="fas fa-edit"></i></a>
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('qurbani-delete')): ?>
                <!--<button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>-->
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">
                    <i class="fa-solid fa-trash"></i>
                </button>
                <?php endif; ?>
                
                <?php endif; ?>
            </form>
        </td>
        <?php endif; ?>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


</table>
<div class="d-flex justify-content-center mt-3">
        <?php echo e($qurbanis->links()); ?>

    </div>

<!-- <p class="text-center text-primary"><small>magnusIdeas.com</small></p> -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sites/20a/5/57972816bd/public_html/sdi_jawhar/resources/views/qurbanis/index.blade.php ENDPATH**/ ?>