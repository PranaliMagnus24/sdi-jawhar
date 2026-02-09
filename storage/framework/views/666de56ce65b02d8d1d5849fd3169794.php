<?php
    $whatsapp = DB::table('whatsapp')->first();
?>

<form action="<?php echo e(route('update.whatsapp.settings')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <div class="row">
        <!-- API Key -->
        <div class="col-md-6">
            <label class="form-label">WhatsApp API Key</label>
            <input type="text" class="form-control" name="apikey" value="<?php echo e($whatsapp->apikey ?? ''); ?>">
        </div>

        <!-- Status -->
        <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="active" <?php echo e(($whatsapp->status ?? '') == 'active' ? 'selected' : ''); ?>>Active</option>
                <option value="inactive" <?php echo e(($whatsapp->status ?? '') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-success mt-3">Save</button>
</form>
<?php /**PATH /home/sites/20a/5/57972816bd/public_html/sdi_jawhar/resources/views/settings/whatsapp.blade.php ENDPATH**/ ?>