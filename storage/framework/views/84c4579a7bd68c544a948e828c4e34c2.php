<?php $__env->startSection('title', 'المراحل التعليمية'); ?>

<?php $__env->startSection('vendor-style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')); ?>">
    <link rel="stylesheet"
        href="<?php echo e(asset('dashboard/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/toastr/toastr.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')); ?>"></script>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js')); ?>"></script>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/toastr/toastr.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
    <script>
        CRUDHelper.init({
            tableSelector: '#stages-table',
            ajaxUrl: '<?php echo e(route('dashboard.stage.datatable')); ?>',
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'operation',
                    name: 'operation',
                    orderable: false,
                    searchable: false
                }
            ],
            delete: {
                selector: '.delete_btn',
                url: '<?php echo e(route('dashboard.stage.destroy', ':id')); ?>',
            },
        });
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">المراحل التعليمية /</span> إدارة
        </h4>
        <a href="<?php echo e(route('dashboard.create.education_stages')); ?>" class="btn btn-primary">إضافة مرحلة تعليمية</a>
    </div>

    <div class="card">
        <h5 class="card-header">جدول المراحل التعليمية</h5>
        <div class="card-datatable table-responsive">
            <table class="dt-responsive table" id="stages-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المرحلة</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/stage/index.blade.php ENDPATH**/ ?>