<?php $__env->startSection('title', 'طرق الشحن'); ?>

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
                <i class="ti ti-truck-delivery me-2"></i>إدارة طرق الشحن
            </h4>
            <a href="<?php echo e(route('dashboard.shipping-methods.create')); ?>" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>إضافة طريقة شحن جديدة
            </a>
        </div>

        <!-- Shipping Methods Table Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>قائمة طرق الشحن
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="shippingTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>المحافظة</th>
                                <th>النوع</th>
                                <th>العنوان</th>
                                <th>الأرقام</th>
                                <th>رسوم الخدمة</th>
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
        $('#shippingTable').DataTable({
            lengthMenu: [
                [10, 25, 50, 100, 200, -1],
                [10, 25, 50, 100, 200, "الكل"]
            ],
            paging: true,
            pageLength: 10,
            stateSave: true,
            stateDuration: -1,
            scrollX: true,
            processing: true,
            serverSide: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
            },
            ajax: '<?php echo e(route('dashboard.shipping-methods.datatable')); ?>',
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    className: 'text-center'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'gov_name',
                    name: 'gov_name',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'type_label',
                    name: 'type_label',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        let colors = {
                            'مكتب بريد': 'info',
                            'توصيل لباب البيت': 'success',
                            'استلام من المكتبة': 'warning'
                        };
                        let color = colors[data] || 'secondary';
                        return '<span class="badge bg-label-' + color + '">' + data + '</span>';
                    }
                },
                {
                    data: 'address',
                    name: 'address'
                },
                {
                    data: 'phones_list',
                    name: 'phones_list',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'fee',
                    name: 'fee',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return '<strong>' + data + ' جنيه</strong>';
                    }
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ]
        });
    });

    function deleteShippingMethod(id) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "لن تتمكن من التراجع عن هذا!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذفه!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/dashboard/shipping-methods/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Content-Type': 'application/json'
                    }
                }).then(resp => {
                    if (resp.ok) {
                        $('#shippingTable').DataTable().ajax.reload();
                        Swal.fire('تم الحذف!', 'تم حذف طريقة الشحن.', 'success');
                    } else {
                        Swal.fire('خطأ!', 'حدث خطأ أثناء الحذف.', 'error');
                    }
                });
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/shipping/index.blade.php ENDPATH**/ ?>