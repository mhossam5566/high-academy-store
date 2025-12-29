

<?php $__env->startSection('title', 'تعديل منتج'); ?>

<?php $__env->startSection('vendor-style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/cssbundle/dropify.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/select2/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
    <script src="<?php echo e(asset('admin/assets/js/bundle/dropify.bundle.js')); ?>"></script>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/select2/select2.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">المنتجات /</span> تعديل
        </h4>
        <a href="<?php echo e(route('dashboard.product')); ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
        </a>
    </div>

    <form id="productForm" data-ajax data-redirect="<?php echo e(route('dashboard.product')); ?>"
        action="<?php echo e(route('dashboard.update.product')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">

        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>المعلومات الأساسية
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Multilingual Name Fields -->
                    <div class="col-12">
                        <label class="form-label fw-bold">اسم المنتج</label>
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <?php $__currentLoopData = config('translatable.locales'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="nav-item">
                                    <button type="button" class="nav-link <?php echo e($index === 0 ? 'active' : ''); ?>"
                                        data-bs-toggle="tab" data-bs-target="#name-<?php echo e($locale); ?>">
                                        <?php echo e(strtoupper($locale)); ?>

                                    </button>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <div class="tab-content">
                            <?php $__currentLoopData = config('translatable.locales'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="tab-pane fade <?php echo e($index === 0 ? 'show active' : ''); ?>"
                                    id="name-<?php echo e($locale); ?>">
                                    <input type="text" name="name:<?php echo e($locale); ?>"
                                        value="<?php echo e($product->translate($locale)->name); ?>"
                                        class="form-control <?php $__errorArgs = ['name:' . $locale];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="أدخل اسم المنتج (<?php echo e(strtoupper($locale)); ?>)">
                                    <?php $__errorArgs = ['name:' . $locale];
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
                                <div class="tab-pane fade <?php echo e($index === 0 ? 'show active' : ''); ?>"
                                    id="desc-<?php echo e($locale); ?>">
                                    <textarea name="description:<?php echo e($locale); ?>" rows="3"
                                        class="form-control <?php $__errorArgs = ['description:' . $locale];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="أدخل الوصف (<?php echo e(strtoupper($locale)); ?>)"><?php echo e($product->translate($locale)->description); ?></textarea>
                                    <?php $__errorArgs = ['description:' . $locale];
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

                    <div class="col-md-6">
                        <label class="form-label">الاسم المختصر</label>
                        <input type="text" name="short_name" value="<?php echo e($product->short_name); ?>" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">تعليق (عندما يكون غير متوفر)</label>
                        <input type="text" name="commit" value="<?php echo e($product->commit); ?>" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Stock -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-currency-dollar me-2"></i>السعر والمخزون
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">السعر</label>
                        <input type="number" name="price" id="price" value="<?php echo e($product->price); ?>" step="0.01"
                            class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">الكمية</label>
                        <input type="number" name="quantity" value="<?php echo e($product->quantity); ?>" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">أقصى كمية للطلب</label>
                        <input type="number" name="max_qty_for_order" value="<?php echo e($product->max_qty_for_order); ?>"
                            class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">قيمة الضريبة</label>
                        <input type="number" name="tax" value="<?php echo e($product->tax); ?>" step="0.01"
                            class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">ضريبة التوصيل البطيء</label>
                        <input type="number" name="slowTax" value="<?php echo e($product->slowTax); ?>" step="0.01"
                            class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Offer Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-discount-2 me-2"></i>إعدادات العروض
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">لديه عرض؟</label>
                        <select name="have_offer" id="have_offer" class="form-select">
                            <option value="0" <?php echo e($product->have_offer == 0 ? 'selected' : ''); ?>>لا</option>
                            <option value="1" <?php echo e($product->have_offer == 1 ? 'selected' : ''); ?>>نعم</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">نوع العرض</label>
                        <select name="offer_type" id="offer_type" class="form-select">
                            <option value="">اختر النوع</option>
                            <option value="percentage" <?php echo e($product->offer_type == 'percentage' ? 'selected' : ''); ?>>نسبة
                                مئوية</option>
                            <option value="value" <?php echo e($product->offer_type == 'value' ? 'selected' : ''); ?>>قيمة ثابتة
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">قيمة العرض</label>
                        <input type="number" name="offer_value" id="offer_value" value="<?php echo e($product->offer_value); ?>"
                            step="0.01" class="form-control">
                    </div>

                    <div class="col-12">
                        <div class="alert alert-info mb-0" id="final_price_alert" style="display: none;">
                            <i class="ti ti-info-circle me-2"></i>
                            <span id="final_price"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories & Classification -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-category me-2"></i>التصنيفات
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">القسم الرئيسي</label>
                        <select name="main_category_id" id="main_categories" class="form-select select2">
                            <option value="">اختر القسم</option>
                            <?php $__currentLoopData = $main_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $main_category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($main_category->id); ?>"
                                    <?php echo e($product->main_category_id == $main_category->id ? 'selected' : ''); ?>>
                                    <?php echo e($main_category->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">المادة الدراسية</label>
                        <select name="category_id" id="category_id" class="form-select select2">
                            <option value="">اختر المادة</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>"
                                    <?php echo e($product->category_id == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->title); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الصف الدراسي</label>
                        <select name="slider_id" id="slider_id" class="form-select select2">
                            <option value="">اختر الصف</option>
                            <?php $__currentLoopData = $sliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($slider->id); ?>"
                                    <?php echo e($product->slider_id == $slider->id ? 'selected' : ''); ?>>
                                    <?php echo e($slider->title); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">المدرس</label>
                        <select name="brand_id" id="brand_id" class="form-select select2">
                            <option value="">اختر المدرس</option>
                            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($brand->id); ?>"
                                    <?php echo e($product->brand_id == $brand->id ? 'selected' : ''); ?>>
                                    <?php echo e($brand->title); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الأحجام</label>
                        <select name="sizes[]" id="size" class="form-select select2" multiple>
                            <?php $__currentLoopData = $sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($size); ?>"
                                    <?php echo e(in_array($size, json_decode($product->sizes ?? '[]')) ? 'selected' : ''); ?>>
                                    <?php echo e($size); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الألوان</label>
                        <select name="colors[]" id="color" class="form-select select2" multiple>
                            <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($color); ?>"
                                    <?php echo e(in_array($color, json_decode($product->colors ?? '[]')) ? 'selected' : ''); ?>>
                                    <?php echo e($color); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-settings me-2"></i>الإعدادات
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">حالة المنتج</label>
                        <select name="is_deleted" class="form-select">
                            <option value="0" <?php echo e($product->is_deleted == 0 ? 'selected' : ''); ?>>نشط</option>
                            <option value="1" <?php echo e($product->is_deleted == 1 ? 'selected' : ''); ?>>مخفي</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">الأكثر مبيعاً</label>
                        <select name="best_seller" id="best_seller" class="form-select">
                            <option value="0" <?php echo e($product->best_seller == 0 ? 'selected' : ''); ?>>لا</option>
                            <option value="1" <?php echo e($product->best_seller == 1 ? 'selected' : ''); ?>>نعم</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-photo me-2"></i>الصور
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">الصورة الرئيسية</label>
                        <input type="file" name="photo" accept="image/*"
                            data-default-file="<?php echo e($product->image_path); ?>" class="dropify">
                        <small class="text-muted">صورة واحدة فقط</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الصور الإضافية</label>
                        <input type="file" name="images[]" accept="image/*" class="dropify" multiple>
                        <small class="text-muted">يمكن رفع أكثر من صورة</small>
                    </div>

                    <?php if($product->images && count($product->images) > 0): ?>
                        <div class="col-12">
                            <label class="form-label">الصور الحالية</label>
                            <div class="row g-2">
                                <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-2">
                                        <div class="position-relative">
                                            <img src="<?php echo e(asset($image->image)); ?>" class="img-thumbnail"
                                                alt="Product Image">
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="card">
            <div class="card-body text-center">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="ti ti-device-floppy me-2"></i>تحديث المنتج
                </button>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
    <script>
        $(document).ready(function() {
            // Initialize plugins
            $('.dropify').dropify();
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: 'اختر...'
            });

            // Price calculation
            function calculateFinalPrice() {
                var price = parseFloat($('#price').val()) || 0;
                var offerType = $('#offer_type').val();
                var offerValue = parseFloat($('#offer_value').val()) || 0;
                var finalPrice;

                if (offerType === "percentage") {
                    finalPrice = price - (price * offerValue / 100);
                } else if (offerType === "value") {
                    finalPrice = price - offerValue;
                } else {
                    $('#final_price_alert').hide();
                    return;
                }

                $('#final_price').text('السعر النهائي بعد الخصم: ' + finalPrice.toFixed(2) + ' جنيه');
                $('#final_price_alert').show();
            }

            $('#offer_type, #price, #offer_value').on('change input', calculateFinalPrice);

            // Calculate on page load
            calculateFinalPrice();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/Product/edit.blade.php ENDPATH**/ ?>