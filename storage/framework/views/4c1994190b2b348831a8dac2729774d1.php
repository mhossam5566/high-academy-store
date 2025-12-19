<?php if(isset($pageConfigs)): ?>
    <?php echo App\Helpers\Helpers::updatePageConfig($pageConfigs); ?>

<?php endif; ?>
<?php
    $configData = App\Helpers\Helpers::appClasses();

    /* Display elements */
    $customizerHidden = $customizerHidden ?? '';

?>



<?php $__env->startSection('layoutContent'); ?>
    <!-- Content -->
    <?php echo $__env->yieldContent('content'); ?>
    <!--/ Content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts/commonMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/layouts/blankLayout.blade.php ENDPATH**/ ?>