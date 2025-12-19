<!-- BEGIN: Vendor JS-->
<script src="<?php echo e(asset('dashboard/assets/vendor/libs/jquery/jquery.js')); ?>"></script>
<script src="<?php echo e(asset('dashboard/assets/vendor/libs/popper/popper.js')); ?>"></script>
<script src="<?php echo e(asset('dashboard/assets/vendor/js/bootstrap.js')); ?>"></script>
<script src="<?php echo e(asset('dashboard/assets/vendor/libs/node-waves/node-waves.js')); ?>"></script>
<script src="<?php echo e(asset('dashboard/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')); ?>"></script>
<script src="<?php echo e(asset('dashboard/assets/vendor/libs/hammer/hammer.js')); ?>"></script>
<script src="<?php echo e(asset('dashboard/assets/vendor/libs/typeahead-js/typeahead.js')); ?>"></script>
<script src="<?php echo e(asset('dashboard/assets/vendor/js/menu.js')); ?>"></script>
<script src="<?php echo e(asset('dashboard/assets/js/form-ajax.js')); ?>"></script>
<script src="<?php echo e(asset('dashboard/assets/js/crud-helper.js')); ?>"></script>
<script src="<?php echo e(asset('dashboard/assets/vendor/libs/toastr/toastr.js')); ?>"></script>

<?php echo $__env->yieldContent('vendor-script'); ?>
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="<?php echo e(asset('dashboard/assets/js/main.js')); ?>"></script>


<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
<?php echo $__env->yieldPushContent('pricing-script'); ?>
<!-- END: Pricing Modal JS-->
<!-- BEGIN: Page JS-->
<?php echo $__env->yieldContent('page-script'); ?>
<!-- END: Page JS-->

<script>
    window.translations = {
        delete_confirm: "<?php echo e(__('dashboard.delete_confirm')); ?>",
        delete_text: "<?php echo e(__('dashboard.delete_text')); ?>",
        delete_btn: "<?php echo e(__('dashboard.delete_btn')); ?>",
        cancel_btn: "<?php echo e(__('dashboard.cancel_btn')); ?>",
        deleting_title: "<?php echo e(__('dashboard.deleting_title')); ?>",
        deleting_wait: "<?php echo e(__('dashboard.deleting_wait')); ?>",
        deleted_title: "<?php echo e(__('dashboard.deleted_title')); ?>",
        deleted_success: "<?php echo e(__('dashboard.deleted_success')); ?>",
        error_title: "<?php echo e(__('dashboard.error_title')); ?>",
        error_text: "<?php echo e(__('dashboard.error_text')); ?>",
        processing: "<?php echo e(__('dashboard.processing')); ?>",
        success: "<?php echo e(__('dashboard.success')); ?>",
        error: "<?php echo e(__('dashboard.error')); ?>",
        unexpected_error: "<?php echo e(__('dashboard.unexpected_error')); ?>",
        operation_success: "<?php echo e(__('dashboard.operation_success')); ?>"
    };
</script>
<?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/layouts/sections/scripts.blade.php ENDPATH**/ ?>