<?php $__env->startSection('title', 'متابعة طلبــــــاتي'); ?>

<?php $__env->startSection('content'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        crossorigin="anonymous">
    <style>
        .order-status {
            font-size: 14px;
            font-weight: bold;
        }

        @media (min-width:576px) {
            .order-status {
                font-size: 1.2rem;
            }
        }

        .text-primary {
            color: #e99239 !important;
        }

        .bg-warning,
        .btn-primary {
            background-color: #e99239 !important;
            border: none;
        }
    </style>

    <div class="container pt-5">
        <div class="col-md-12 mt-5 text-center">
            <h5 class="section-title position-relative text-uppercase mb-3"><span class="pr-3">طلبــــــاتي</span></h5>
        </div>

        <div class="row mx-auto text-center justify-content-center align-items-center my-3 g-3">
            <div class="col-6 col-md">
                <div class="d-flex flex-column align-items-center">
                    <div class="d-flex align-items-center justify-content-center bg-secondary text-white rounded-circle"
                        style="width: 80px; height: 80px; font-size: 20px;">
                        <?php echo e($reservedCount); ?>

                    </div>
                    <h4 class="mt-2 order-status">الطلبات المحجوزة</h4>
                </div>
            </div>
            <div class="col-6 col-md">
                <div class="d-flex flex-column align-items-center">
                    <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle"
                        style="width: 80px; height: 80px; font-size: 20px;">
                        <?php echo e($successCount); ?>

                    </div>
                    <p class="mt-2 order-status">الطلبات الناجحة</p>
                </div>
            </div>
            <div class="col-6 col-md">
                <div class="d-flex flex-column align-items-center">
                    <div class="d-flex align-items-center justify-content-center bg-info text-white rounded-circle"
                        style="width: 80px; height: 80px; font-size: 20px;">
                        <?php echo e($pendingCount); ?>

                    </div>
                    <p class="mt-2 order-status">الطلبات المعلقة</p>
                </div>
            </div>

            <div class="col-6 col-md">
                <div class="d-flex flex-column align-items-center">
                    <div class="d-flex align-items-center justify-content-center bg-danger text-white rounded-circle"
                        style="width: 80px; height: 80px; font-size: 20px;">
                        <?php echo e($cancelledCount); ?>

                    </div>
                    <p class="mt-2 order-status">الطلبات الملغية</p>
                </div>
            </div>

            <div class="col-6 col-md">
                <div class="d-flex flex-column align-items-center">
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle"
                        style="width: 80px; height: 80px; font-size: 20px;">
                        <?php echo e($successPercentage); ?>%
                    </div>
                    <p class="mt-2 order-status">نسبة النجاح</p>
                </div>
            </div>
        </div>

        <?php if($orders->isEmpty()): ?>
            <h3 class="text-center">لا يوجد أي طلبات حالياً</h3>
        <?php endif; ?>

        <div class="row g-4">
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $ship = optional($o->shipping); ?>
                <div class="col-md-6">
                    <div class="card h-100" dir="rtl">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>طلب رقم : <?php echo e($o->id); ?></strong>
                            <?php switch($o->status):
                                case ('new'): ?>
                                    <span class="badge bg-warning text-dark">طلب جديد</span>
                                <?php break; ?>

                                <?php case ('success'): ?>
                                    <span class="badge bg-success">طلب ناجح</span>
                                <?php break; ?>

                                <?php case ('cancelled'): ?>
                                    <span class="badge bg-danger">طلب ملغي</span>
                                <?php break; ?>

                                <?php case ('reserved'): ?>
                                    <span class="badge bg-info">طلب محجوز</span>
                                <?php break; ?>
                                <?php case ('pending'): ?>
                                    <span class="badge bg-info">طلب معلق</span>
                                <?php break; ?>

                                <?php default: ?>
                                    <span class="badge bg-secondary">غير معروف</span>
                            <?php endswitch; ?>
                        </div>

                        <div class="card-body">
                            
                            <div class="d-flex justify-content-between mb-2">
                                <strong>طريقة الشحن</strong>
                                <span class="text-primary">
                                    <?php echo e($ship->name ?? '-'); ?>

                                    (
                                    <?php if($ship->type === 'post'): ?>
                                        مكتب بريد
                                    <?php elseif($ship->type === 'home'): ?>
                                        توصيل لباب البيت
                                    <?php elseif($ship->type === 'branch'): ?>
                                        استلام من المكتبة
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                    )
                                </span>
                            </div>

                            
                            <div class="d-flex justify-content-between mb-2">
                                <strong>عنوان الشحن</strong>
                                <span><?php echo e($ship->address ?? '-'); ?></span>
                            </div>

                            
                            <div class="d-flex justify-content-between mb-2">
                                <strong>أرقام التواصل</strong>
                                <span><?php echo e($ship->phones ? implode(' - ', $ship->phones) : '-'); ?></span>
                            </div>

                            
                            <div class="d-flex justify-content-between mb-2">
                                <strong>المبلغ المدفوع</strong>
                                <span><?php echo e(number_format($o->total, 2)); ?> جنيه</span>
                            </div>

                            
                            <div class="d-flex justify-content-between mb-2">
                                <strong>طريقة الدفع</strong>
                                <span><?php echo e($o->method); ?></span>
                            </div>

                            
                            <div class="d-flex justify-content-between mb-2">
                                <strong>توقيت الطلب</strong>
                                <span><?php echo e($o->created_at->format('Y-m-d H:i')); ?></span>
                            </div>

                            
                            <?php if($o->status === 'success'): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>تاريخ الاستلام المتوقع</strong>
                                    <?php if($ship->type === 'home'): ?>
                                        <span class="text-success">خلال 3 أيام عمل</span>
                                    <?php else: ?>
                                        <span class="text-success">من 3 إلى 5 أيام عمل</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <a href="<?php echo e(route('user.order.details', $o->id)); ?>"
                            class="card-footer text-center text-decoration-none">
                            <h5>انقر لعرض تفاصيل الطلب</h5>
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/user/myorders.blade.php ENDPATH**/ ?>