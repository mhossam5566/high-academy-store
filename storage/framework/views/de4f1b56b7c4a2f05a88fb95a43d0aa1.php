<?php $__env->startSection('title', 'إضافة مرحلة تعليمية'); ?>

<?php $__env->startSection('vendor-style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/toastr/toastr.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js')); ?>"></script>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/toastr/toastr.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
    <script src="<?php echo e(asset('dashboard/assets/js/form-ajax.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">المراحل التعليمية /</span> إضافة مرحلة جديدة
        </h4>
    </div>

    <form action="<?php echo e(route('dashboard.store.stage')); ?>" method="POST" data-validate class="needs-validation" novalidate
        data-ajax data-redirect="<?php echo e(route('dashboard.education_stages')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="card mb-4">
            <h5 class="card-header">بيانات المرحلة التعليمية</h5>
            <div class="card-body">
                <div class="tab-content pt-3">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php $__currentLoopData = config('translatable.locales'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if($loop->first): ?> active <?php endif; ?>" data-bs-toggle="tab"
                                    href="#stage-<?php echo e($locale); ?>" role="tab">
                                    <?php echo e(strtoupper($locale)); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                    <?php $__currentLoopData = config('translatable.locales'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="tab-pane fade <?php if($loop->first): ?> show active <?php endif; ?> mt-3"
                            id="stage-<?php echo e($locale); ?>" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">اسم المرحلة التعليمية (<?php echo e(strtoupper($locale)); ?>)</label>
                                    <input type="text" class="form-control" name="title:<?php echo e($locale); ?>">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <h5 class="card-header">إعدادات إضافية</h5>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label d-block mb-1">حالة التفعيل</label>
                        <label class="switch">
                            <input type="checkbox" class="switch-input" name="is_active" value="1" checked>
                            <span class="switch-toggle-slider">
                                <span class="switch-on"><i class="ti ti-check"></i></span>
                                <span class="switch-off"><i class="ti ti-x"></i></span>
                            </span>
                            <span class="switch-label">مفعل / غير مفعل</span>
                        </label>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label d-block mb-1">صورة المرحلة (اختياري)</label>
                        <input type="file" name="photo" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-end">
                <button type="submit" class="btn btn-primary">حفظ المرحلة</button>
            </div>
        </div>

    </form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/stage/create.blade.php ENDPATH**/ ?>