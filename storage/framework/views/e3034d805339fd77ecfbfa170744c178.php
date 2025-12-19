<?php if(isset($pageConfigs)): ?>
    <?php echo App\Helpers\Helpers::updatePageConfig($pageConfigs); ?>

<?php endif; ?>
<?php
    $configData = App\Helpers\Helpers::appClasses();
?>

<?php if(isset($configData['layout'])): ?>
    <?php echo $__env->make(
        $configData['layout'] === 'horizontal'
            ? 'dashboard.layouts.horizontalLayout'
            : ($configData['layout'] === 'blank'
                ? 'dashboard.layouts.blankLayout'
                : ($configData['layout'] === 'front'
                    ? 'dashboard.layouts.layoutFront'
                    : 'dashboard.layouts.contentNavbarLayout')), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/layouts/layoutMaster.blade.php ENDPATH**/ ?>