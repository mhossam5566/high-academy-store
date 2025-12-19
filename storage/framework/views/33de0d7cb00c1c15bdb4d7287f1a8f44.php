<?php $__env->startSection('title', 'تعديل الطلب #' . $order->id); ?>

<?php $__env->startSection('vendor-style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/select2/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js')); ?>"></script>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/select2/select2.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">الطلبات /</span> تعديل الطلب #<?php echo e($order->id); ?>

        </h4>
        <a href="<?php echo e(route('dashboard.orders')); ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
        </a>
    </div>

    <!-- Edit Order Items Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-shopping-cart me-2"></i>تعديل المنتجات
            </h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('dashboard.updateOrderBook', $order->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>المنتج</th>
                                <th>السعر</th>
                                <th>الكمية</th>
                                <th>المبلغ الإجمالي</th>
                                <th>إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($item->products->short_name); ?></strong>
                                        <input type="hidden" name="items[<?php echo e($index); ?>][id]" value="<?php echo e($item->id); ?>">
                                    </td>
                                    <td><?php echo e($item->price); ?> جنيه</td>
                                    <td>
                                        <input type="number" name="items[<?php echo e($index); ?>][amount]" 
                                               value="<?php echo e($item->amout); ?>" min="1" 
                                               class="form-control" style="max-width: 100px;">
                                    </td>
                                    <td><strong><?php echo e($item->total_price); ?> جنيه</strong></td>
                                    <td>
                                        <button type="submit" name="remove" value="<?php echo e($item->id); ?>" 
                                                class="btn btn-sm btn-danger">
                                            <i class="ti ti-trash me-1"></i>حذف
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <!-- Add New Product Row -->
                            <tr class="table-success">
                                <td>
                                    <select id="productSelect" name="new_item[product_id]" class="form-select" style="width: 100%;">
                                        <option value="">اختر منتج جديد</option>
                                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($product->id); ?>"><?php echo e($product->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </td>
                                <td class="text-muted">—</td>
                                <td>
                                    <input type="number" name="new_item[amount]" min="1" value="1" 
                                           class="form-control" style="max-width: 100px;">
                                </td>
                                <td class="text-muted">—</td>
                                <td>
                                    <button type="submit" name="add" value="1" class="btn btn-sm btn-success">
                                        <i class="ti ti-plus me-1"></i>إضافة
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">الإجمـــالي:</th>
                                <th><?php echo e($order->amount); ?> جنيه</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i>حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Order Details Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-info-circle me-2"></i>تعديل تفاصيل الطلب
            </h5>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo e(route('dashboard.updateOrder', $order->id)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('put'); ?>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">رقم الطلب</label>
                        <input type="text" class="form-control" value="<?php echo e($order->id); ?>" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الاسم</label>
                        <input class="form-control" value="<?php echo e($order->name); ?>" name="name" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">رقم الموبايل</label>
                        <input class="form-control" value="<?php echo e($order->mobile); ?>" name="mobile" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">العنوان</label>
                        <input class="form-control" value="<?php echo e($order->address); ?>" name="address">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">العنوان التفصيلي</label>
                        <input class="form-control" value="<?php echo e($order->address2); ?>" name="address2">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">أقرب مكتب بريد</label>
                        <input class="form-control" value="<?php echo e($order->near_post); ?>" name="near_post">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">نوع الشحن</label>
                        <select name="shipping_method" class="form-select" required>
                            <?php $__currentLoopData = \App\Models\ShippingMethod::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($method->id); ?>"
                                    <?php echo e($order->shipping_method == $method->id ? 'selected' : ''); ?>>
                                    <?php echo e($method->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">قيمة المنتجات</label>
                        <input type="text" class="form-control" value="<?php echo e($order->amount); ?> جنيه" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">رسوم الشحن</label>
                        <input type="text" class="form-control" value="<?php echo e($order->delivery_fee); ?> جنيه" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">إجمالي المدفوع</label>
                        <input type="text" class="form-control text-success fw-bold" value="<?php echo e($order->total); ?> جنيه" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">وسيلة الدفع</label>
                        <input type="text" class="form-control" value="<?php echo e($order->method); ?>" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">رقم الحساب المحول منه</label>
                        <input type="text" class="form-control" value="<?php echo e($order->account); ?>" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">حالة الطلب</label>
                        <div class="pt-2">
                            <?php switch($order->status):
                                case ('new'): ?>
                                    <span class="badge bg-warning fs-6">طلب جديد</span>
                                <?php break; ?>

                                <?php case ('success'): ?>
                                    <span class="badge bg-success fs-6">طلب ناجح</span>
                                <?php break; ?>

                                <?php case ('cancelled'): ?>
                                    <span class="badge bg-danger fs-6">طلب ملغي</span>
                                <?php break; ?>

                                <?php case ('pending'): ?>
                                    <span class="badge bg-info fs-6">طلب معلق</span>
                                <?php break; ?>

                                <?php case ('reserved'): ?>
                                    <span class="badge bg-primary fs-6">طلب محجوز</span>
                                <?php break; ?>

                                <?php default: ?>
                                    <span class="badge bg-secondary fs-6">حالة غير معروفة</span>
                            <?php endswitch; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">وقت الطلب</label>
                        <input type="text" class="form-control" value="<?php echo e($order->created_at); ?>" disabled>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i>تعديل الطلب
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Actions Card -->
    <?php if($order->status == 'new'): ?>
        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-success confirmorder" id="accept">
                        <i class="ti ti-check me-1"></i>تأكيد الطلب
                    </button>
                    <button class="btn btn-danger deleteorder" id="cancle">
                        <i class="ti ti-x me-1"></i>رفض الطلب
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#productSelect').select2({
                placeholder: "🔍 ابحث عن منتج...",
                allowClear: true,
                theme: 'bootstrap-5',
                dir: "rtl",
                width: '100%'
            });
        });

        /***** DELETE order ******/
        $('.deleteorder').on("click", function() {
            var itemId = <?php echo e($order->id); ?>;
            var csrf = $('meta[name="csrf-token"]').attr('content');
            
            Swal.fire({
                title: "هل أنت متأكد؟",
                text: "سيتم رفض الطلب",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "متأكد",
                cancelButtonText: "إلغاء",
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "جاري الرفض",
                        text: "يتم الآن رفض الطلب",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                            $.ajax({
                                url: "<?php echo e(route('dashboard.changestate')); ?>",
                                type: "POST",
                                contentType: "application/json",
                                data: JSON.stringify({
                                    _token: csrf,
                                    id: itemId,
                                    state: 2
                                }),
                                success: function(data) {
                                    Swal.fire({
                                        title: "تم الرفض",
                                        text: "تم رفض الطلب بنجاح",
                                        icon: "success",
                                    }).then(() => {
                                        location.reload(true);
                                    });
                                },
                                error: function(error) {
                                    console.error("Error:", error);
                                    Swal.fire({
                                        title: "خطأ",
                                        text: "خطأ أثناء رفض الطلب",
                                        icon: "error",
                                    });
                                },
                            });
                        },
                    });
                }
            });
        });

        /***** Accept order ******/
        $('.confirmorder').on("click", function() {
            var itemId = <?php echo e($order->id); ?>;
            var csrf = $('meta[name="csrf-token"]').attr('content');
            
            Swal.fire({
                title: "هل أنت متأكد؟",
                text: "سيتم تأكيد الطلب",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "متأكد",
                cancelButtonText: "إلغاء",
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "جاري التأكيد",
                        text: "يتم الآن تأكيد الطلب",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                            $.ajax({
                                url: "<?php echo e(route('dashboard.changestate')); ?>",
                                type: "POST",
                                contentType: "application/json",
                                data: JSON.stringify({
                                    _token: csrf,
                                    id: itemId,
                                    state: 1
                                }),
                                success: function(data) {
                                    Swal.fire({
                                        title: "تم التأكيد",
                                        text: "تم تأكيد الطلب بنجاح",
                                        icon: "success",
                                    }).then(() => {
                                        location.reload(true);
                                    });
                                },
                                error: function(error) {
                                    console.error("Error:", error);
                                    Swal.fire({
                                        title: "خطأ",
                                        text: "خطأ أثناء تأكيد الطلب",
                                        icon: "error",
                                    });
                                },
                            });
                        },
                    });
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/order/edit.blade.php ENDPATH**/ ?>