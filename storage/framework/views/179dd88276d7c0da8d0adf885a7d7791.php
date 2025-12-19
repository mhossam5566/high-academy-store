<?php $__env->startSection('title', 'تعديل مدرس'); ?>

<?php $__env->startSection('vendor-style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/cssbundle/dropify.min.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
    <script src="<?php echo e(asset('admin/assets/js/bundle/dropify.bundle.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">المدرسين /</span> تعديل
        </h4>
        <a href="<?php echo e(route('dashboard.teachers')); ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
        </a>
    </div>

    <form id="brandForm" data-ajax data-redirect="<?php echo e(route('dashboard.teachers')); ?>"
          action="<?php echo e(route('dashboard.teachers.update')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="brand_id" value="<?php echo e($brand->id); ?>">

        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>معلومات المدرس
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Multilingual Title Fields -->
                    <div class="col-12">
                        <label class="form-label fw-bold">اسم المدرس</label>
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <?php $__currentLoopData = config('translatable.locales'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="nav-item">
                                    <button type="button" class="nav-link <?php echo e($index === 0 ? 'active' : ''); ?>"
                                            data-bs-toggle="tab" data-bs-target="#title-<?php echo e($locale); ?>">
                                        <?php echo e(strtoupper($locale)); ?>

                                    </button>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <div class="tab-content">
                            <?php $__currentLoopData = config('translatable.locales'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="tab-pane fade <?php echo e($index === 0 ? 'show active' : ''); ?>" id="title-<?php echo e($locale); ?>">
                                    <input type="text" name="title:<?php echo e($locale); ?>"
                                           value="<?php echo e($brand->translate($locale)->title); ?>"
                                           class="form-control <?php $__errorArgs = ['title:'.$locale];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="أدخل اسم المدرس (<?php echo e(strtoupper($locale)); ?>)">
                                    <?php $__errorArgs = ['title:'.$locale];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Multilingual Description Fields -->
                    <div class="col-12">
                        <label class="form-label fw-bold">الوصف</label>
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <?php $__currentLoopData = config('translatable.locales'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="nav-item">
                                    <button type="button" class="nav-link <?php echo e($index === 0 ? 'active' : ''); ?>"
                                            data-bs-toggle="tab" data-bs-target="#desc-<?php echo e($locale); ?>">
                                        <?php echo e(strtoupper($locale)); ?>

                                    </button>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <div class="tab-content">
                            <?php $__currentLoopData = config('translatable.locales'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="tab-pane fade <?php echo e($index === 0 ? 'show active' : ''); ?>" id="desc-<?php echo e($locale); ?>">
                                    <textarea name="description:<?php echo e($locale); ?>" rows="3"
                                              class="form-control <?php $__errorArgs = ['description:'.$locale];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                              placeholder="أدخل الوصف (<?php echo e(strtoupper($locale)); ?>)"><?php echo e($brand->translate($locale)->description); ?></textarea>
                                    <?php $__errorArgs = ['description:'.$locale];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Upload -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-photo me-2"></i>صورة المدرس
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">الصورة الشخصية</label>
                        <input type="file" name="photo" class="dropify form-control <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               data-default-file="<?php echo e($brand->image_path); ?>"
                               data-max-file-size="2M" data-allowed-file-extensions="jpg jpeg png gif">
                        <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <?php if($brand->image_path): ?>
                    <div class="mt-3">
                        <label class="form-label">الصورة الحالية:</label>
                        <div>
                            <img src="<?php echo e($brand->image_path); ?>" alt="Current Photo" 
                                 class="img-thumbnail" style="max-width: 150px;">
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('dashboard.teachers')); ?>" class="btn btn-label-secondary">
                        <i class="ti ti-x me-1"></i>إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check me-1"></i>تحديث
                    </button>
                </div>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
    <script>
        $(document).ready(function() {
            // Initialize Dropify
            $('.dropify').dropify({
                messages: {
                    'default': 'اسحب وأفلت الملف هنا أو انقر',
                    'replace': 'اسحب وأفلت أو انقر للاستبدال',
                    'remove':  'إزالة',
                    'error':   'عذراً، حدث خطأ ما'
                },
                error: {
                    'fileSize': 'حجم الملف كبير جداً',
                    'minWidth': 'العرض صغير جداً',
                    'maxWidth': 'العرض كبير جداً',
                    'minHeight': 'الارتفاع صغير جداً',
                    'maxHeight': 'الارتفاع كبير جداً',
                    'imageFormat': 'صيغة الصورة غير مسموح بها'
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/Brand/edit.blade.php ENDPATH**/ ?>