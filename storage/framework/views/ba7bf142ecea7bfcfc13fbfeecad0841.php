<?php $__env->startSection('title'); ?>
    shipping methods
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <style>
        .shipping-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #e9ecef;
            height: 200px;
            display: flex;
            align-items: center;
        }
        .shipping-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            border-color: #007bff;
        }
        .shipping-card .card-body {
            text-align: center;
            padding: 2rem;
        }
        .shipping-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .page-title {
            text-align: center;
            margin-bottom: 3rem;
            color: #495057;
        }
    </style>
    
    <div class="container-fluid my-5">
        <div class="row">
            <div class="col-12">
                <h2 class="page-title">اختر طريقة الشحن لعرض الطلبات</h2>
            </div>
        </div>
        
        <!-- Shipping Method Cards -->
        <div class="row justify-content-center">
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card shipping-card" onclick="window.location.href='<?php echo e(route('dashboard.orders.barcode.list')); ?>?shipping=all'">
                    <div class="card-body">
                        <i class="fa fa-list shipping-icon text-primary"></i>
                        <h4 class="card-title">جميع الطلبات</h4>
                        <p class="card-text text-muted">عرض كافة الطلبات بجميع طرق الشحن</p>
                    </div>
                </div>
            </div>
            
            <?php
                $shippingTypes = \App\Models\ShippingMethod::select('type')->distinct()->get();
                $typeLabels = [
                    'branch' => 'استلام من الفرع',
                    'post' => 'البريد المصري',
                    'home' => 'التوصيل للمنزل'
                ];
                $typeIcons = [
                    'branch' => 'fa-building',
                    'post' => 'fa-envelope',
                    'home' => 'fa-home'
                ];
            ?>
            
            <?php $__currentLoopData = $shippingTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card shipping-card" onclick="window.location.href='<?php echo e(route('dashboard.orders.barcode.list')); ?>?shipping=<?php echo e($type->type); ?>'">
                    <div class="card-body">
                        <i class="fa <?php echo e($typeIcons[$type->type] ?? 'fa-truck'); ?> shipping-icon text-success"></i>
                        <h4 class="card-title"><?php echo e($typeLabels[$type->type] ?? $type->type); ?></h4>
                        <p class="card-text text-muted">عرض طلبات <?php echo e($typeLabels[$type->type] ?? $type->type); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/admin/barcode/index.blade.php ENDPATH**/ ?>