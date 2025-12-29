<?php $__env->startSection('title', 'لوحة التحكم - الإحصائيات'); ?>

<?php $__env->startSection('vendor-style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/apex-charts/apex-charts.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/apex-charts/apexcharts.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">
                    <i class="ti ti-dashboard me-2"></i>لوحة التحكم
                </h4>
                <p class="text-muted mb-0">مرحباً بك في نظام إدارة الطلبات</p>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('dashboard.index')); ?>" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="ti ti-calendar me-1"></i>من تاريخ
                        </label>
                        <input type="date" class="form-control" name="date_from" value="<?php echo e($dateFrom); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="ti ti-calendar me-1"></i>إلى تاريخ
                        </label>
                        <input type="date" class="form-control" name="date_to" value="<?php echo e($dateTo); ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="ti ti-filter me-1"></i>تصفية
                        </button>
                        <a href="<?php echo e(route('dashboard.index')); ?>" class="btn btn-label-secondary">
                            <i class="ti ti-refresh me-1"></i>إعادة تعيين
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Key Statistics -->
        <div class="row mb-4">
            <!-- Total Products -->
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted d-block mb-1">إجمالي المنتجات</span>
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 me-2"><?php echo e(number_format($statistics['products']['total'])); ?></h3>
                                </div>
                                <small class="text-success">
                                    <i class="ti ti-circle-check"></i>
                                    <?php echo e($statistics['products']['active']); ?> نشط
                                </small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-package ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted d-block mb-1">إجمالي العملاء</span>
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 me-2"><?php echo e(number_format($statistics['users']['total'])); ?></h3>
                                </div>
                                <small class="text-info">
                                    <i class="ti ti-user-plus"></i>
                                    <?php echo e($statistics['users']['new_this_month']); ?> جديد هذا الشهر
                                </small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ti ti-users ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Order Value -->
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted d-block mb-1">متوسط قيمة الطلب</span>
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 me-2"><?php echo e(number_format($statistics['revenue']['average'], 2)); ?></h3>
                                </div>
                                <small class="text-muted">جنيه</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ti ti-calculator ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Growth Rate -->
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted d-block mb-1">معدل النمو</span>
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 me-2 <?php echo e($statistics['revenue']['growth'] >= 0 ? 'text-success' : 'text-danger'); ?>">
                                        <?php echo e(number_format(abs($statistics['revenue']['growth']), 1)); ?>%
                                    </h3>
                                </div>
                                <small class="<?php echo e($statistics['revenue']['growth'] >= 0 ? 'text-success' : 'text-danger'); ?>">
                                    <i class="ti <?php echo e($statistics['revenue']['growth'] >= 0 ? 'ti-trending-up' : 'ti-trending-down'); ?>"></i>
                                    مقارنة بالفترة السابقة
                                </small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-<?php echo e($statistics['revenue']['growth'] >= 0 ? 'success' : 'danger'); ?>">
                                    <i class="ti ti-chart-line ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Cards -->
        <div class="row mb-4">
            <!-- Total Revenue -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted">إجمالي الإيرادات</span>
                                <div class="d-flex align-items-center my-2">
                                    <h3 class="mb-0 me-2"><?php echo e(number_format($statistics['revenue']['total'], 2)); ?></h3>
                                    <span class="text-success">جنيه</span>
                                </div>
                                <small class="mb-0">من الطلبات المدفوعة</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ti ti-currency-pound ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today Revenue -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted">إيرادات اليوم</span>
                                <div class="d-flex align-items-center my-2">
                                    <h3 class="mb-0 me-2"><?php echo e(number_format($statistics['revenue']['today'], 2)); ?></h3>
                                    <span class="text-success">جنيه</span>
                                </div>
                                <small class="mb-0">مبيعات <?php echo e(now()->format('Y/m/d')); ?></small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ti ti-calendar-stats ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Week Revenue -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted">إيرادات الأسبوع</span>
                                <div class="d-flex align-items-center my-2">
                                    <h3 class="mb-0 me-2"><?php echo e(number_format($statistics['revenue']['week'], 2)); ?></h3>
                                    <span class="text-success">جنيه</span>
                                </div>
                                <small class="mb-0">آخر 7 أيام</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-calendar-week ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Month Revenue -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted">إيرادات الشهر</span>
                                <div class="d-flex align-items-center my-2">
                                    <h3 class="mb-0 me-2"><?php echo e(number_format($statistics['revenue']['month'], 2)); ?></h3>
                                    <span class="text-success">جنيه</span>
                                </div>
                                <small class="mb-0">شهر <?php echo e(now()->format('m/Y')); ?></small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ti ti-chart-line ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Statistics -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-shopping-cart me-2"></i>إحصائيات الطلبات العادية
                        </h5>
                        <a href="<?php echo e(route('dashboard.orders')); ?>" class="btn btn-sm btn-primary">
                            <i class="ti ti-eye me-1"></i>عرض الكل
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Total Orders -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            <i class="ti ti-package ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0"><?php echo e($statistics['orders']['total']); ?></h4>
                                        <small class="text-muted">إجمالي الطلبات</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Orders -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-warning">
                                            <i class="ti ti-clock ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0"><?php echo e($statistics['orders']['pending']); ?></h4>
                                        <small class="text-muted">قيد الانتظار</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Delivered Orders -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-success">
                                            <i class="ti ti-circle-check ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0"><?php echo e($statistics['orders']['delivered']); ?></h4>
                                        <small class="text-muted">تم التوصيل</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Cancelled Orders -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-danger">
                                            <i class="ti ti-circle-x ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0"><?php echo e($statistics['orders']['cancelled']); ?></h4>
                                        <small class="text-muted">ملغية</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="row mt-3">
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-label-success">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-0"><?php echo e($statistics['orders']['paid']); ?></h5>
                                                <small>طلبات مدفوعة</small>
                                            </div>
                                            <i class="ti ti-check ti-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-label-warning">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-0"><?php echo e($statistics['orders']['unpaid']); ?></h5>
                                                <small>طلبات غير مدفوعة</small>
                                            </div>
                                            <i class="ti ti-alert-circle ti-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Voucher Orders Statistics -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-ticket me-2"></i>إحصائيات طلبات الكوبونات
                        </h5>
                        <a href="<?php echo e(route('dashboard.voucher_order')); ?>" class="btn btn-sm btn-primary">
                            <i class="ti ti-eye me-1"></i>عرض الكل
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Total Voucher Orders -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            <i class="ti ti-ticket ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0"><?php echo e($statistics['vouchers']['total']); ?></h4>
                                        <small class="text-muted">إجمالي الطلبات</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Vouchers -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-warning">
                                            <i class="ti ti-clock ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0"><?php echo e($statistics['vouchers']['pending']); ?></h4>
                                        <small class="text-muted">قيد الانتظار</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Success Vouchers -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-info">
                                            <i class="ti ti-check ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0"><?php echo e($statistics['vouchers']['success']); ?></h4>
                                        <small class="text-muted">ناجحة</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Completed Vouchers -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-success">
                                            <i class="ti ti-circle-check ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0"><?php echo e($statistics['vouchers']['completed']); ?></h4>
                                        <small class="text-muted">مكتملة</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="row">
            <!-- Recent Regular Orders -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-clock me-2"></i>آخر الطلبات العادية
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>العميل</th>
                                        <th>الإجمالي</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $statistics['orders']['recent']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo e(route('dashboard.orders.details', $order->id)); ?>"
                                                    class="text-primary">
                                                    #<?php echo e($order->id); ?>

                                                </a>
                                            </td>
                                            <td><?php echo e($order->user->name ?? ($order->name ?? 'N/A')); ?></td>
                                            <td><?php echo e(number_format($order->total, 2)); ?> جنيه</td>
                                            <td>
                                                <?php if($order->status == 'delivered'): ?>
                                                    <span class="badge bg-secondary">تم التوصيل</span>
                                                <?php elseif($order->status == 'pending'): ?>
                                                    <span class="badge bg-secondary">قيد الانتظار</span>
                                                <?php elseif($order->status == 'cancelled'): ?>
                                                    <span class="badge bg-secondary">ملغي</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?php echo e($order->status); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($order->created_at->format('Y/m/d')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="ti ti-inbox ti-lg"></i>
                                                <p class="mb-0 mt-2">لا توجد طلبات حالياً</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Voucher Orders -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-clock me-2"></i>آخر طلبات الكوبونات
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>العميل</th>
                                        <th>الكمية</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $statistics['vouchers']['recent']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voucher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo e(route('dashboard.voucher_order.details', $voucher->id)); ?>"
                                                    class="text-primary">
                                                    #<?php echo e($voucher->id); ?>

                                                </a>
                                            </td>
                                            <td><?php echo e($voucher->user_name); ?></td>
                                            <td><?php echo e($voucher->quantity); ?></td>
                                            <td>
                                                <?php if($voucher->state == 'completed'): ?>
                                                    <span class="badge bg-success">مكتملة</span>
                                                <?php elseif($voucher->state == 'success'): ?>
                                                    <span class="badge bg-info">ناجحة</span>
                                                <?php elseif($voucher->state == 'pending'): ?>
                                                    <span class="badge bg-warning">قيد الانتظار</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?php echo e($voucher->state); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($voucher->created_at->format('Y/m/d')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="ti ti-inbox ti-lg"></i>
                                                <p class="mb-0 mt-2">لا توجد طلبات حالياً</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-chart-bar me-2"></i>الإيرادات الشهرية
                        </h5>
                        <span class="badge bg-label-primary">آخر 6 شهور</span>
                    </div>
                    <div class="card-body">
                        <div id="revenueChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Count Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-chart-line me-2"></i>عدد الطلبات الشهرية
                        </h5>
                        <span class="badge bg-label-success">آخر 6 شهور</span>
                    </div>
                    <div class="card-body">
                        <div id="ordersCountChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Orders Status Chart -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-chart-pie me-2"></i>توزيع حالات الطلبات
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="ordersStatusChart"></div>
                    </div>
                </div>
            </div>

            <!-- Vouchers Status Chart -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-chart-donut me-2"></i>توزيع حالات الكوبونات
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="vouchersStatusChart"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
    <script>
        // Revenue Chart
        const revenueChartOptions = {
            series: [{
                name: 'الإيرادات',
                data: [
                    <?php $__currentLoopData = $statistics['monthly']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e(number_format($month['revenue'], 0, '', '')); ?>,
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ]
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: ['#696cff'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: [
                    <?php $__currentLoopData = $statistics['monthly']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        '<?php echo e($month['month_ar']); ?>',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                labels: {
                    style: {
                        fontSize: '13px'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return val.toLocaleString() + ' جنيه';
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 5
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val.toLocaleString() + ' جنيه';
                    }
                }
            }
        };

        // Orders Count Chart
        const ordersCountChartOptions = {
            series: [{
                name: 'عدد الطلبات',
                data: [
                    <?php $__currentLoopData = $statistics['monthly']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($month['orders']); ?>,
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ]
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    columnWidth: '45%',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                offsetY: -25,
                style: {
                    fontSize: '12px',
                    colors: ['#304758']
                }
            },
            colors: ['#56ca00'],
            xaxis: {
                categories: [
                    <?php $__currentLoopData = $statistics['monthly']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        '<?php echo e($month['month_ar']); ?>',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                labels: {
                    style: {
                        fontSize: '13px'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return Math.round(val);
                    }
                },
                title: {
                    text: 'عدد الطلبات'
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 5
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + ' طلب';
                    }
                }
            }
        };

        // Orders Status Chart
        const ordersStatusChart = {
            series: [
                <?php echo e($statistics['orders']['pending']); ?>,
                <?php echo e($statistics['orders']['delivered']); ?>,
                <?php echo e($statistics['orders']['cancelled']); ?>

            ],
            labels: ['قيد الانتظار', 'تم التوصيل', 'ملغي'],
            chart: {
                type: 'donut',
                height: 300
            },
            colors: ['#ffab00', '#56ca00', '#ff4c51'],
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            value: {
                                fontSize: '24px',
                                fontWeight: 600
                            },
                            total: {
                                show: true,
                                label: 'الإجمالي',
                                fontSize: '14px',
                                formatter: function(w) {
                                    return <?php echo e($statistics['orders']['total']); ?>

                                }
                            }
                        }
                    }
                }
            }
        };

        // Vouchers Status Chart
        const vouchersStatusChart = {
            series: [
                <?php echo e($statistics['vouchers']['pending']); ?>,
                <?php echo e($statistics['vouchers']['success']); ?>,
                <?php echo e($statistics['vouchers']['completed']); ?>

            ],
            labels: ['قيد الانتظار', 'ناجحة', 'مكتملة'],
            chart: {
                type: 'donut',
                height: 300
            },
            colors: ['#ffab00', '#16b1ff', '#56ca00'],
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            value: {
                                fontSize: '24px',
                                fontWeight: 600
                            },
                            total: {
                                show: true,
                                label: 'الإجمالي',
                                fontSize: '14px',
                                formatter: function(w) {
                                    return <?php echo e($statistics['vouchers']['total']); ?>

                                }
                            }
                        }
                    }
                }
            }
        };

        // Render Charts
        if (typeof ApexCharts !== 'undefined') {
            const revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueChartOptions);
            revenueChart.render();

            const ordersCountChart = new ApexCharts(document.querySelector("#ordersCountChart"), ordersCountChartOptions);
            ordersCountChart.render();

            const ordersChart = new ApexCharts(document.querySelector("#ordersStatusChart"), ordersStatusChart);
            ordersChart.render();

            const vouchersChart = new ApexCharts(document.querySelector("#vouchersStatusChart"), vouchersStatusChart);
            vouchersChart.render();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/home/dashboard.blade.php ENDPATH**/ ?>