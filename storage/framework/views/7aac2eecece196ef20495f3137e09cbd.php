<?php $__env->startSection('title', 'تعديل صف دراسي'); ?>

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
            <span class="text-muted fw-light">الصفوف الدراسية /</span> تعديل صف دراسي
        </h4>
    </div>

    <form action="<?php echo e(route('dashboard.slider.update')); ?>" method="POST" data-validate class="needs-validation" novalidate
        data-ajax data-redirect="<?php echo e(route('dashboard.slider')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <input type="hidden" name="slider_id" value="<?php echo e($slider->id); ?>">

        <div class="card mb-4">
            <h5 class="card-header">بيانات الصف الدراسي</h5>
            <div class="card-body">
                <div class="tab-content pt-3">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php $__currentLoopData = config('translatable.locales'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if($loop->first): ?> active <?php endif; ?>" data-bs-toggle="tab"
                                    href="#slider-<?php echo e($locale); ?>" role="tab">
                                    <?php echo e(strtoupper($locale)); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                    <?php $__currentLoopData = config('translatable.locales'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="tab-pane fade <?php if($loop->first): ?> show active <?php endif; ?> mt-3"
                            id="slider-<?php echo e($locale); ?>" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">عنوان الصف الدراسي (<?php echo e(strtoupper($locale)); ?>)</label>
                                    <input type="text" class="form-control" name="title:<?php echo e($locale); ?>"
                                        value="<?php echo e(optional($slider->translate($locale))->title); ?>">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <h5 class="card-header">ربط المرحلة التعليمية</h5>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">المرحلة الدراسية</label>
                        <select name="stage_id" id="stage" class="form-select">
                            <option value="">اختر المرحلة</option>
                            <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($s->id); ?>"
                                    <?php if(isset($slider->stage) && $slider->stage->id == $s->id): ?> selected <?php endif; ?>>
                                    <?php echo e($s->title); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-end">
                <button type="submit" class="btn btn-primary">تحديث السلايدر</button>
            </div>
        </div>

    </form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/slider/edit.blade.php ENDPATH**/ ?>