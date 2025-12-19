<?php $__env->startSection('title', 'إضافة محافظة جديدة'); ?>

<?php $__env->startSection('vendor-style'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">إضافة محافظة جديدة</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard.index')); ?>">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard.governorates.index')); ?>">المحافظات</a></li>
                <li class="breadcrumb-item active">إضافة محافظة جديدة</li>
            </ol>
        </nav>
    </div>
    <a href="<?php echo e(route('dashboard.governorates.index')); ?>" class="btn btn-secondary">
        <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
    </a>
</div>

<!-- Governorate Form Card -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="ti ti-map-pin me-2"></i>بيانات المحافظة
        </h5>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('dashboard.governorates.store')); ?>" method="POST" id="governorateForm">
            <?php echo csrf_field(); ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="governorate_name_ar" class="form-label">الاسم باللغة العربية <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control <?php $__errorArgs = ['governorate_name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="governorate_name_ar"
                               name="governorate_name_ar"
                               value="<?php echo e(old('governorate_name_ar')); ?>"
                               placeholder="أدخل اسم المحافظة بالعربية"
                               required>
                        <?php $__errorArgs = ['governorate_name_ar'];
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

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="governorate_name_en" class="form-label">الاسم باللغة الإنجليزية <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control <?php $__errorArgs = ['governorate_name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="governorate_name_en"
                               name="governorate_name_en"
                               value="<?php echo e(old('governorate_name_en')); ?>"
                               placeholder="Enter governorate name in English"
                               required>
                        <?php $__errorArgs = ['governorate_name_en'];
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

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="home_shipping_price" class="form-label">
                            <i class="ti ti-home me-1"></i>سعر التوصيل للمنزل (جنيه) <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               class="form-control <?php $__errorArgs = ['home_shipping_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="home_shipping_price"
                               name="home_shipping_price"
                               value="<?php echo e(old('home_shipping_price', 0)); ?>"
                               placeholder="0.00"
                               step="0.01"
                               min="0"
                               required>
                        <?php $__errorArgs = ['home_shipping_price'];
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

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="post_shipping_price" class="form-label">
                            <i class="ti ti-mailbox me-1"></i>سعر التوصيل لمكتب البريد (جنيه) <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               class="form-control <?php $__errorArgs = ['post_shipping_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="post_shipping_price"
                               name="post_shipping_price"
                               value="<?php echo e(old('post_shipping_price', 0)); ?>"
                               placeholder="0.00"
                               step="0.01"
                               min="0"
                               required>
                        <?php $__errorArgs = ['post_shipping_price'];
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

            <div class="d-flex justify-content-between">
                <a href="<?php echo e(route('dashboard.governorates.index')); ?>" class="btn btn-secondary">
                    <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i>حفظ المحافظة
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
<script>
    // Form validation
    document.getElementById('governorateForm').addEventListener('submit', function(e) {
        const nameAr = document.getElementById('governorate_name_ar').value.trim();
        const nameEn = document.getElementById('governorate_name_en').value.trim();
        const homePrice = document.getElementById('home_shipping_price').value;
        const postPrice = document.getElementById('post_shipping_price').value;

        if (nameAr === '') {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'يجب إدخال اسم المحافظة بالعربية'
            });
            return false;
        }

        if (nameEn === '') {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'يجب إدخال اسم المحافظة بالإنجليزية'
            });
            return false;
        }

        if (homePrice === '' || parseFloat(homePrice) < 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'يجب إدخال سعر التوصيل للمنزل بشكل صحيح'
            });
            return false;
        }

        if (postPrice === '' || parseFloat(postPrice) < 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'يجب إدخال سعر التوصيل لمكتب البريد بشكل صحيح'
            });
            return false;
        }

        return true;
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/governorates/create.blade.php ENDPATH**/ ?>