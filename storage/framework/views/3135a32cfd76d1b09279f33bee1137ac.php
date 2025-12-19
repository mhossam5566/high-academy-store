<?php $__env->startSection('title', 'تفاصيل الطلب #' . $order->id); ?>

<?php $__env->startSection('vendor-style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">الطلبات /</span> تفاصيل الطلب #<?php echo e($order->id); ?>

        </h4>
        <a href="<?php echo e(route('dashboard.orders')); ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
        </a>
    </div>

    <!-- Order Items Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-shopping-cart me-2"></i>المنتجات المطلوبة
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>المنتج</th>
                            <th>السعر</th>
                            <th>الكمية</th>
                            <th>اللون</th>
                            <th>الحجم</th>
                            <th>المبلغ الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($item->products->short_name); ?></strong></td>
                                <td><?php echo e($item->price); ?> جنيه</td>
                                <td><?php echo e($item->amout); ?></td>
                                <td><?php echo e($item->color ?? '-'); ?></td>
                                <td><?php echo e($item->size ?? '-'); ?></td>
                                <td><strong><?php echo e($item->total_price); ?> جنيه</strong></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="5" class="text-end">الإجمـــالي:</th>
                            <th><?php echo e($order->amount); ?> جنيه</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Order Details Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-info-circle me-2"></i>تفاصيل الطلب
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h6 class="text-muted mb-3">معلومات العميل</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="40%">رقم الطلب:</td>
                                <td><strong>#<?php echo e($order->id); ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">الاسم:</td>
                                <td><strong><?php echo e($order->name); ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">رقم الموبايل:</td>
                                <td><strong><?php echo e($order->mobile); ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">العنوان:</td>
                                <td><?php echo e($order->address); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">العنوان التفصيلي:</td>
                                <td><?php echo e($order->address2); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">أقرب مكتب بريد:</td>
                                <td><?php echo e($order->near_post); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h6 class="text-muted mb-3">معلومات الشحن والدفع</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="40%">نوع الشحن:</td>
                                <td>
                                    <?php if($order->shipping): ?>
                                        <strong><?php echo e($order->shipping->name); ?></strong>
                                    <?php elseif($order->shipping_method): ?>
                                        <strong><?php echo e($order->shipping_method); ?></strong>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">قيمة المنتجات:</td>
                                <td><strong><?php echo e($order->amount); ?> جنيه</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">رسوم الشحن:</td>
                                <td><strong><?php echo e($order->delivery_fee); ?> جنيه</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">إجمالي المدفوع:</td>
                                <td class="text-success"><strong><?php echo e($order->total); ?> جنيه</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">وسيلة الدفع:</td>
                                <td><strong><?php echo e($order->method); ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">رقم الحساب:</td>
                                <td><?php echo e($order->account); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">حالة الطلب</h6>
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
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">وقت الطلب</h6>
                        <p class="mb-0"><i class="ti ti-clock me-1"></i><?php echo e($order->created_at); ?></p>
                    </div>
                </div>
            </div>
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

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/order/details.blade.php ENDPATH**/ ?>