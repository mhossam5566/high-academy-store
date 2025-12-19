

<?php $__env->startSection('title', 'تفاصيل طلب الكوبون'); ?>

<?php $__env->startSection('vendor-style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/toastr/toastr.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js')); ?>"></script>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/toastr/toastr.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">طلبات الكوبونات /</span> تفاصيل الطلب
        </h4>
        <a href="<?php echo e(route('dashboard.voucher_order')); ?>" class="btn btn-secondary">العودة للقائمة</a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">الكوبون المطلوب</h5>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>الكوبون</th>
                                <th>السعر</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo e($coupon->name); ?></td>
                                <td><?php echo e($coupon->price); ?> جنيه</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-4">
                <h5 class="card-header">معلومات العميل</h5>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="30%">اسم العميل</th>
                                <td><?php echo e($order->user_name ?? $order->user->name ?? 'غير متوفر'); ?></td>
                            </tr>
                            <tr>
                                <th>البريد الإلكتروني</th>
                                <td><?php echo e($order->user_email ?? $order->user->email ?? 'غير متوفر'); ?></td>
                            </tr>
                            <tr>
                                <th>رقم الهاتف</th>
                                <td><?php echo e($order->user_phone ?? $order->user->phone ?? 'غير متوفر'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-4">
                <h5 class="card-header">تفاصيل الطلب</h5>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="30%">رقم الطلب</th>
                                <td><?php echo e($order->id); ?></td>
                            </tr>
                            <tr>
                                <th>الكمية</th>
                                <td><?php echo e($order->quantity); ?></td>
                            </tr>
                            <tr>
                                <th>السعر الإجمالي</th>
                                <td><?php echo e($coupon->price * $order->quantity); ?> جنيه</td>
                            </tr>
                            <tr>
                                <th>وسيلة الدفع</th>
                                <td><?php echo e($order->method); ?></td>
                            </tr>
                            <tr>
                                <th>رقم الحساب المحول منه</th>
                                <td><?php echo e($order->account); ?></td>
                            </tr>
                            <tr>
                                <th>صورة التحويل</th>
                                <td>
                                    <?php if($order->image): ?>
                                        <img src="<?php echo e(asset('images/reciept/') . '/' . $order->image); ?>" alt="image" class="img-thumbnail" style="max-width: 300px;">
                                    <?php else: ?>
                                        <span class="text-muted">لا توجد صورة</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>حالة الطلب</th>
                                <td>
                                    <?php switch($order->state):
                                        case ('pending'): ?>
                                            <span class="badge bg-warning">منتظر التحقق</span>
                                        <?php break; ?>
                                        <?php case ('success'): ?>
                                            <span class="badge bg-success">طلب ناجح</span>
                                        <?php break; ?>
                                        <?php case ('cancelled'): ?>
                                            <span class="badge bg-danger">طلب ملغي</span>
                                        <?php break; ?>
                                        <?php default: ?>
                                            <span class="badge bg-secondary">حالة غير معروفة</span>
                                    <?php endswitch; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>وقت الطلب</th>
                                <td><?php echo e($order->created_at); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if($order->state == 'pending'): ?>
                <div class="card">
                    <div class="card-body text-center">
                        <button class="btn btn-success me-2 confirmorder">تأكيد الطلب</button>
                        <button class="btn btn-danger deleteorder">رفض الطلب</button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
    <script>
        $(document).ready(function() {
            var csrf = $('meta[name="csrf-token"]').attr('content');
            var itemId = <?php echo e($order->id); ?>;

            $('.deleteorder').on("click", function() {
                Swal.fire({
                    title: "هل انت متأكد",
                    text: "سيتم رفض الطلب",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "متأكد",
                    cancelButtonText: "الغاء",
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
                                    url: "<?php echo e(route('dashboard.voucher_order.changestate')); ?>",
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
                                            text: "خطأ أثناء الرفض",
                                            icon: "error",
                                        });
                                    },
                                });
                            },
                        });
                    }
                });
            });

            $('.confirmorder').on("click", function() {
                Swal.fire({
                    title: "هل انت متأكد",
                    text: "سيتم تأكيد الطلب",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "متأكد",
                    cancelButtonText: "الغاء",
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
                                    url: "<?php echo e(route('dashboard.voucher_order.changestate')); ?>",
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
                                            text: "خطأ أثناء التأكيد",
                                            icon: "error",
                                        });
                                    },
                                });
                            },
                        });
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/voucher_order/details.blade.php ENDPATH**/ ?>