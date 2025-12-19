

<?php $__env->startSection('title', 'إضافة قسيمة خصم'); ?>

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
                <span class="text-muted fw-light">القسائم /</span> إضافة قسيمة جديدة
            </h4>
            <a href="<?php echo e(route('dashboard.discount')); ?>" class="btn btn-secondary">
                <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
            </a>
        </div>

        <!-- Create Coupon Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-ticket me-2"></i>معلومات القسيمة
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="couponForm" method="POST" action="<?php echo e(route('dashboard.discount.store')); ?>">
                            <?php echo csrf_field(); ?>
                            
                            <!-- Coupon Code -->
                            <div class="mb-4">
                                <label class="form-label" for="code">
                                    <i class="ti ti-barcode me-1"></i>رمز القسيمة
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       id="code"
                                       name="code" 
                                       class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       required
                                       placeholder="مثال: SUMMER2025">
                                <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted">رمز فريد لتعريف القسيمة</small>
                            </div>

                            <!-- Discount Amount -->
                            <div class="mb-4">
                                <label class="form-label" for="discount">
                                    <i class="ti ti-coin me-1"></i>قيمة الخصم (بالجنيه)
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       id="discount"
                                       name="discount" 
                                       class="form-control <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       required 
                                       min="1" 
                                       step="0.01" 
                                       placeholder="مثال: 50">
                                <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted">المبلغ المحدد الذي سيتم خصمه عند استخدام القسيمة</small>
                            </div>

                            <!-- Usage Limit -->
                            <div class="mb-4">
                                <label class="form-label" for="usage_limit">
                                    <i class="ti ti-users me-1"></i>حد الاستخدام
                                    <small class="text-muted">(اختياري)</small>
                                </label>
                                <input type="number" 
                                       id="usage_limit"
                                       name="usage_limit" 
                                       class="form-control <?php $__errorArgs = ['usage_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       min="1" 
                                       placeholder="مثال: 100">
                                <?php $__errorArgs = ['usage_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted">عدد المرات التي يمكن استخدام القسيمة فيها (اتركه فارغاً لاستخدام غير محدود)</small>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ti ti-device-floppy me-1"></i>حفظ القسيمة
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
    $(document).ready(function() {
        $('#couponForm').submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            let submitBtn = $(this).find('button[type="submit"]');
            
            // Disable submit button
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>جاري الحفظ...');

            $.ajax({
                url: '<?php echo e(route('dashboard.discount.store')); ?>',
                type: "POST",
                dataType: "json",
                data: formData,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم الحفظ',
                        text: 'تم حفظ القسيمة بنجاح',
                        confirmButtonText: 'موافق'
                    }).then(() => {
                        window.location.href = "<?php echo e(route('dashboard.discount')); ?>";
                    });
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i>حفظ القسيمة');
                    
                    let errorMessage = '';
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '<br>';
                        });
                    } else {
                        errorMessage = xhr.responseJSON?.error || xhr.responseText || 'حدث خطأ ما!';
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        html: errorMessage,
                        confirmButtonText: 'موافق'
                    });
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/discount/create.blade.php ENDPATH**/ ?>