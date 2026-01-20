<?php $__env->startSection('title'); ?>
    السلة
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="<?php echo e(route('user.home')); ?>">الرئيسيه</a>
                    <span class="breadcrumb-item active">سلة التسوق</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Cart section -->
    <div class="container my-5" id="cart-list">
        <?php echo $__env->make('user.layouts._cart-list', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/user/cart.blade.php ENDPATH**/ ?>