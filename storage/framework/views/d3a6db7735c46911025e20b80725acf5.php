<?php $__env->startSection('content'); ?>
    <style>
        .btn-sm {
            width: 10px;
            height: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    <div class="container">
        <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                <h2 class="text-center text-md-start">Ramadan Collection List</h2>
            </div>
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <div class="d-flex justify-content-end">
                    <a class="btn btn-success btn-small me-2" href="<?php echo e(route('collection.create')); ?>">
                        <i class="fa fa-plus"></i>
                    </a>
                    <a href="<?php echo e(route('export.collections', request()->all())); ?>" class="btn btn-success btn-small">
                        <i class="fas fa-share-square"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="<?php echo e(route('collectionlist')); ?>">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" value="<?php echo e(request('name')); ?>" class="form-control"
                                placeholder="Name">
                        </td>
                        <td>
                            <label for="contact">Contact:</label>
                            <input type="text" id="contact" name="contact" value="<?php echo e(request('contact')); ?>"
                                class="form-control" placeholder="Contact">
                        </td>
                        <td>
                            <label for="receipt_book">Receipt Number:</label>
                            <input type="text" id="receipt_book" name="receipt_book" value="<?php echo e(request('receipt_book')); ?>"
                                class="form-control" placeholder="Receipt Number">
                        </td>
                        <td>
                            <label for="donationcategory">Category:</label>
                            <select name="donationcategory" class="form-control">
                                <option value="">Select Category</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->name); ?>" <?php echo e(request('donationcategory') == $category->name ? 'selected' : ''); ?>>
                                        <?php echo e($category->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </td>
                        <!-- Payment Mode Filter -->
                        <td>
                            <label for="payment_mode">Payment Mode:</label>
                            <select name="payment_mode" class="form-control">
                                <option value="">Select Payment Mode</option>
                                <option value="Cash" <?php echo e(request('payment_mode') == 'Cash' ? 'selected' : ''); ?>>Cash</option>
                                <option value="Online" <?php echo e(request('payment_mode') == 'Online' ? 'selected' : ''); ?>>Online
                                </option>
                            </select>
                        </td>
                        <td>
                            <label for="collected_by">Collected By:</label>
                            <select name="collected_by" class="form-control">
                                <option value="">Collected By</option>
                                <?php $__currentLoopData = $collectedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" <?php echo e(request('collected_by') == $user->id ? 'selected' : ''); ?>>
                                        <?php echo e($user->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </td>
                        <td class="text-nowrap">
                            <button type="submit" class="btn btn-primary mt-4">Filter</button>
                            <a href="<?php echo e(route('collectionlist')); ?>" class="btn btn-secondary mt-4 ms-2">Reset</a>
                        </td>
                    </tr>
                </table>
            </div>
        </form>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <!-- Collection List -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>

                        <!-- Sortable Date Column -->
                        <th>
                            Date
                            <a
                                href="<?php echo e(route('collectionlist', ['sort_by' => 'date', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'date' ? 'desc' : 'asc'] + request()->except('page'))); ?>">
                                <i
                                    class="fa fa-sort<?php echo e(request('sort_by') === 'date' ? '-' . request('sort_order') : ''); ?>"></i>
                            </a>
                        </th>

                        <!-- Sortable Name Column -->
                        <th>
                            Name
                            <a
                                href="<?php echo e(route('collectionlist', ['sort_by' => 'name', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'name' ? 'desc' : 'asc'] + request()->except('page'))); ?>">
                                <i
                                    class="fa fa-sort<?php echo e(request('sort_by') === 'name' ? '-' . request('sort_order') : ''); ?>"></i>
                            </a>
                            <?php if(request('sort_by') === 'name'): ?>
                                <i class="fa fa-sort-<?php echo e(request('sort_order') === 'asc' ? 'up' : 'down'); ?>"></i>
                            <?php endif; ?>
                            </a>
                        </th>

                        <th>Donation Category</th>
                        <th>Payment Mode</th>
                        <th>Collected By</th>

                        <!-- Sortable Amount Column -->
                        <th>
                            Amount
                            <a
                                href="<?php echo e(route('collectionlist', ['sort_by' => 'amount', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'amount' ? 'desc' : 'asc'] + request()->except('page'))); ?>">
                                <i
                                    class="fa fa-sort<?php echo e(request('sort_by') === 'amount' ? '-' . request('sort_order') : ''); ?>"></i>
                            </a>
                            <?php if(request('sort_by') === 'amount'): ?>
                                <i class="fa fa-sort-<?php echo e(request('sort_order') === 'asc' ? 'up' : 'down'); ?>"></i>
                            <?php endif; ?>
                            </a>
                        </th>

                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($collection->date); ?></td>
                            <td><?php echo e($collection->name); ?></td>
                            <td><?php echo e($collection->donationcategory); ?></td>
                            <td><?php echo e($collection->payment_mode); ?></td>
                            <td><?php echo e($collection->user->name ?? 'N/A'); ?></td>
                            <td><?php echo e($collection->amount); ?></td>
                            <td>
                                <div class="d-flex justify-content-center flex-wrap gap-1">
                                    <a class="btn btn-info btn-sm d-flex align-items-center justify-content-center"
                                        style="width: 25px; height: 25px;"
                                        href="<?php echo e(route('collection.show', $collection->id)); ?>">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('collection.edit', $collection->id)); ?>"
                                        class="btn btn-primary btn-sm d-flex align-items-center justify-content-center"
                                        style="width: 25px; height: 25px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <a class="btn btn-success btn-sm d-flex align-items-center justify-content-center"
                                        style="width: 25px; height: 25px;"
                                        href="<?php echo e(route('collection.view', base64_encode($collection->id))); ?>">
                                        <i class="fas fa-file"></i>
                                    </a>
                                    <form action="<?php echo e(route('collection.destroy', $collection->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                            class="btn btn-danger btn-sm d-flex align-items-center justify-content-center"
                                            style="width: 25px; height: 25px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination links -->
        <div class="d-flex justify-content-center mt-3">
            <?php echo e($collections->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    function openPDF(url) {
        var win = window.open(url, '_blank');
        if (win) {
            win.focus();
        } else {
            alert('Please allow pop-ups for this website');
        }
    }
</script>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\sdi_jawhar\resources\views/ramzan/collectionlist.blade.php ENDPATH**/ ?>