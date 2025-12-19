<?php $__env->startSection('title', 'تعديل طريقة الشحن'); ?>

<?php $__env->startSection('vendor-style'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <span class="text-muted fw-light">طرق الشحن /</span> تعديل طريقة الشحن #<?php echo e($shippingMethod->id); ?>

            </h4>
            <a href="<?php echo e(route('dashboard.shipping-methods')); ?>" class="btn btn-secondary">
                <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
            </a>
        </div>

        <!-- Edit Shipping Method Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-truck-delivery me-2"></i>تعديل معلومات طريقة الشحن
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo e(route('dashboard.shipping-methods.update', $shippingMethod->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            
                            <!-- Name -->
                            <div class="mb-4">
                                <label class="form-label" for="name">
                                    <i class="ti ti-tag me-1"></i>الاسم
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       id="name"
                                       name="name" 
                                       class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('name', $shippingMethod->name)); ?>" 
                                       required
                                       placeholder="مثال: شحن القاهرة">
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

                            <!-- Type -->
                            <div class="mb-4">
                                <label class="form-label" for="type">
                                    <i class="ti ti-category me-1"></i>نوع الشحن
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="type" name="type" class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="">اختر النوع</option>
                                    <option value="post" <?php echo e(old('type', $shippingMethod->type) == 'post' ? 'selected' : ''); ?>>مكتب بريد</option>
                                    <option value="home" <?php echo e(old('type', $shippingMethod->type) == 'home' ? 'selected' : ''); ?>>توصيل لباب البيت</option>
                                    <option value="branch" <?php echo e(old('type', $shippingMethod->type) == 'branch' ? 'selected' : ''); ?>>استلام من المكتبة</option>
                                </select>
                                <?php $__errorArgs = ['type'];
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

                            <!-- Fee -->
                            <div class="mb-4">
                                <label class="form-label" for="fee">
                                    <i class="ti ti-coin me-1"></i>رسوم الخدمة (جنيه)
                                </label>
                                <input type="number" 
                                       id="fee"
                                       name="fee" 
                                       step="0.01" 
                                       class="form-control <?php $__errorArgs = ['fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('fee', $shippingMethod->fee ?? '0.00')); ?>"
                                       placeholder="0.00">
                                <?php $__errorArgs = ['fee'];
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

                            <!-- Governorate -->
                            <div class="mb-4">
                                <label class="form-label" for="government">
                                    <i class="ti ti-map-pin me-1"></i>المحافظة
                                </label>
                                <select id="government" name="government" class="form-select <?php $__errorArgs = ['government'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">اختر المحافظة</option>
                                    <?php $__currentLoopData = $govs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($g['id']); ?>" <?php echo e(old('government', $shippingMethod->government) == $g['id'] ? 'selected' : ''); ?>>
                                            <?php echo e($g['governorate_name_ar']); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['government'];
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

                            <!-- Address -->
                            <div class="mb-4">
                                <label class="form-label" for="address">
                                    <i class="ti ti-home me-1"></i>العنوان
                                </label>
                                <input type="text" 
                                       id="address"
                                       name="address" 
                                       class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('address', $shippingMethod->address)); ?>"
                                       placeholder="مثال: شارع التحرير، ميدان رمسيس">
                                <?php $__errorArgs = ['address'];
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

                            <!-- Phones -->
                            <div class="mb-4">
                                <label class="form-label" for="phones">
                                    <i class="ti ti-phone me-1"></i>أرقام الهاتف
                                </label>
                                <input type="text" 
                                       id="phones" 
                                       name="phones" 
                                       class="form-control <?php $__errorArgs = ['phones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('phones', is_array($shippingMethod->phones) ? implode(',', $shippingMethod->phones) : ($shippingMethod->phones ?? ''))); ?>"
                                       placeholder="أضف رقماً ثم اضغط Enter">
                                <?php $__errorArgs = ['phones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted">اضغط Enter بعد كل رقم لإضافته</small>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ti ti-device-floppy me-1"></i>تحديث طريقة الشحن
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js"></script>
<script>
    $(function() {
        $('#phones').tagsInput({
            width: '100%',
            height: '75px',
            interactive: true,
            defaultText: 'أضف رقم',
            removeWithBackspace: true,
            minChars: 1,
            maxChars: 15,
            placeholderColor: '#666'
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-style'); ?>
<style>
    .tagsinput {
        border: 1px solid #d9dee3;
        border-radius: 0.375rem;
        padding: 0.5rem;
        min-height: 75px;
    }
    
    .tagsinput .tag {
        background: var(--bs-primary);
        color: white;
        border-radius: 0.25rem;
        padding: 0.25rem 0.5rem;
        margin: 0.25rem;
        display: inline-block;
    }
    
    .tagsinput .tag a {
        color: white;
        margin-left: 0.5rem;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/shipping/edit.blade.php ENDPATH**/ ?>