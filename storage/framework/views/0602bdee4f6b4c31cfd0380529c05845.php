<?php $__env->startSection('title', 'إضافة مدينة جديدة'); ?>

<?php $__env->startSection('vendor-style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <span class="text-muted fw-light">المدن /</span> إضافة مدينة جديدة
            </h4>
            <a href="<?php echo e(route('dashboard.cities.index', $selectedGovernorate ? ['governorate' => $selectedGovernorate->id] : [])); ?>" class="btn btn-secondary">
                <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
            </a>
        </div>

        <!-- Create City Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-map-pin me-2"></i>معلومات المدينة
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('dashboard.cities.store')); ?>" method="POST" id="cityForm">
                            <?php echo csrf_field(); ?>

                            <!-- Governorate -->
                            <div class="mb-4">
                                <label for="governorate_id" class="form-label">
                                    <i class="ti ti-map me-1"></i>المحافظة
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select <?php $__errorArgs = ['governorate_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="governorate_id"
                                        name="governorate_id"
                                        required>
                                    <option value="">اختر المحافظة</option>
                                    <?php $__currentLoopData = $governorates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $governorate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($governorate->id); ?>"
                                            <?php echo e((int) old('governorate_id', optional($selectedGovernorate)->id) === (int) $governorate->id ? 'selected' : ''); ?>>
                                            <?php echo e($governorate->name_ar); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['governorate_id'];
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

                            <div class="row">
                                <!-- Arabic Name -->
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="name_ar" class="form-label">
                                            <i class="ti ti-language me-1"></i>الاسم باللغة العربية
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control <?php $__errorArgs = ['name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               id="name_ar"
                                               name="name_ar"
                                               value="<?php echo e(old('name_ar')); ?>"
                                               placeholder="مثال: القاهرة"
                                               required>
                                        <?php $__errorArgs = ['name_ar'];
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

                                <!-- English Name -->
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="name_en" class="form-label">
                                            <i class="ti ti-language me-1"></i>الاسم باللغة الإنجليزية
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control <?php $__errorArgs = ['name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               id="name_en"
                                               name="name_en"
                                               value="<?php echo e(old('name_en')); ?>"
                                               placeholder="Example: Cairo"
                                               required>
                                        <?php $__errorArgs = ['name_en'];
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

                            <!-- Status -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="status"
                                           name="status"
                                           value="1"
                                           <?php if(old('status', true)): echo 'checked'; endif; ?>>
                                    <label class="form-check-label" for="status">
                                        <i class="ti ti-check me-1"></i>المدينة نشطة
                                    </label>
                                </div>
                                <small class="text-muted">تفعيل المدينة يسمح للعملاء باختيارها عند الطلب</small>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ti ti-device-floppy me-1"></i>حفظ المدينة
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
<script>
    const governorateSelect = document.getElementById('governorate_id');
    const defaultGovernorate = <?php echo json_encode(old('governorate_id', optional($selectedGovernorate)->id), 512) ?>;

    if (defaultGovernorate) {
        governorateSelect.value = defaultGovernorate.toString();
    }

    document.getElementById('cityForm').addEventListener('submit', function (event) {
        const governorate = governorateSelect.value.trim();
        const nameAr = document.getElementById('name_ar').value.trim();
        const nameEn = document.getElementById('name_en').value.trim();

        if (!governorate) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'يرجى اختيار المحافظة',
                confirmButtonText: 'موافق'
            });
            return;
        }

        if (!nameAr) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'يرجى إدخال اسم المدينة بالعربية',
                confirmButtonText: 'موافق'
            });
            return;
        }

        if (!nameEn) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'يرجى إدخال اسم المدينة بالإنجليزية',
                confirmButtonText: 'موافق'
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/cities/create.blade.php ENDPATH**/ ?>