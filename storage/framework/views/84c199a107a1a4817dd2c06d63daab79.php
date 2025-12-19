

<?php $__env->startSection('title', 'تعديل قسم رئيسي'); ?>

<?php $__env->startSection('vendor-style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/cssbundle/dropify.min.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
    <script src="<?php echo e(asset('admin/assets/js/bundle/dropify.bundle.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">الأقسام الرئيسية /</span> تعديل
        </h4>
        <a href="<?php echo e(route('dashboard.main_categories')); ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
        </a>
    </div>

    <form id="mainCategoryForm" data-ajax data-redirect="<?php echo e(route('dashboard.main_categories')); ?>"
          action="<?php echo e(url('dashboard/main_categories/update')); ?>/<?php echo e($category->id); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <input type="hidden" name="category_id" value="<?php echo e($category->id); ?>">

        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>معلومات القسم الرئيسي
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">اسم القسم الرئيسي</label>
                        <input type="text" name="name" value="<?php echo e($category->name); ?>"
                               class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="أدخل اسم القسم الرئيسي" required>
                        <?php $__errorArgs = ['name'];
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
                </div>
            </div>
        </div>

        <!-- Image Upload -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-photo me-2"></i>صورة القسم
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">صورة القسم الرئيسي</label>
                        <?php
                            $defaultImage = $category->icon_image ? asset('storage/' . $category->icon_image) : '';
                        ?>
                        <input type="file" name="icon_image" class="dropify form-control <?php $__errorArgs = ['icon_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               data-default-file="<?php echo e($defaultImage); ?>"
                               data-max-file-size="2M" data-allowed-file-extensions="jpg jpeg png gif svg">
                        <?php $__errorArgs = ['icon_image'];
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

                <?php if($category->icon_image): ?>
                    <div class="mt-3">
                        <label class="form-label">الصورة الحالية:</label>
                        <div>
                            <img src="<?php echo e(asset('storage/' . $category->icon_image)); ?>" alt="Current Icon" 
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
                    <a href="<?php echo e(route('dashboard.main_categories')); ?>" class="btn btn-label-secondary">
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

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/main_category/edit.blade.php ENDPATH**/ ?>