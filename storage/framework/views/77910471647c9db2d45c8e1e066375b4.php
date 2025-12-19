<?php $__env->startSection('title', 'إدارة المحافظات'); ?>

<?php $__env->startSection('vendor-style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')); ?>"></script>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="ti ti-map-pin me-2"></i>إدارة المحافظات
            </h4>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('dashboard.governorates.create')); ?>" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>إضافة محافظة
                </a>
            </div>
        </div>

        <!-- Governorates Table Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>قائمة المحافظات
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="governoratesTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم بالعربية</th>
                                <th>الاسم بالإنجليزية</th>
                                <th>أسعار الشحن</th>
                                <th>عدد المدن</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
<script>
    $(document).ready(function() {
        var columns = [
            { 
                data: 'id', 
                name: 'id',
                className: 'text-center'
            },
            { 
                data: 'name_ar', 
                name: 'name_ar' 
            },
            { 
                data: 'name_en', 
                name: 'name_en' 
            },
            { 
                data: 'shipping_price', 
                name: 'shipping_price',
                className: 'text-center'
            },
            { 
                data: 'cities_count', 
                name: 'cities_count',
                className: 'text-center'
            },
            { 
                data: 'actions', 
                name: 'actions', 
                orderable: false, 
                searchable: false,
                className: 'text-center'
            }
        ];

        var table = $('#governoratesTable').DataTable({
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "الكل"]],
            "pageLength": 10,
            "stateSave": true,
            "stateDuration": -1,
            'scrollX': true,
            "processing": true,
            "serverSide": true,
            "sort": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
            },
            "ajax": {
                "url": "<?php echo e(route('dashboard.governorates.datatable')); ?>",
                "type": "GET"
            },
            "columns": columns
        });
    });

    function deleteGovernorate(id) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'لن تتمكن من استرجاع هذه المحافظة بعد الحذف!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo e(route("dashboard.governorates.destroy", ":id")); ?>'.replace(':id', id),
                    type: 'DELETE',
                    data: { _token: '<?php echo e(csrf_token()); ?>' },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('تم الحذف!', response.message, 'success');
                            $('#governoratesTable').DataTable().ajax.reload();
                        } else {
                            Swal.fire('خطأ!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('خطأ!', 'حدث خطأ أثناء حذف المحافظة', 'error');
                    }
                });
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/governorates/index.blade.php ENDPATH**/ ?>