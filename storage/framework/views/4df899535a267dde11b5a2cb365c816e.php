<?php $__env->startSection('title', 'العروض'); ?>

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
                <i class="ti ti-discount-2 me-2"></i>إدارة العروض
            </h4>
            <a href="<?php echo e(route('dashboard.create.offers')); ?>" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>إضافة عرض جديد
            </a>
        </div>

        <!-- Offers Table Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>قائمة العروض
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="offersTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الصورة</th>
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
    // Set up CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    console.log('Initializing DataTable...');
    
    const table = $('#offersTable').DataTable({
        processing: true,
        serverSide: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
        },
        ajax: {
            url: "<?php echo e(route('dashboard.offers.datatable')); ?>",
            type: 'GET',
            dataType: 'json',
            data: function(d) {
                console.log('Sending request to server with parameters:', d);
            },
            dataSrc: function(json) {
                console.log('Received response from server:', json);
                return json.data;
            },
            error: function(xhr, error, thrown) {
                console.group('DataTables Error');
                console.error('Status:', xhr.status);
                console.error('Error:', error);
                console.error('Thrown:', thrown);
                console.error('Response:', xhr.responseText);
                console.groupEnd();
                
                if (xhr.status === 401) {
                    window.location.href = '<?php echo e(route("login")); ?>';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'حدث خطأ أثناء تحميل البيانات. يرجى التحقق من الكونسول.',
                        confirmButtonText: 'موافق'
                    });
                }
            }
        },
        columns: [
            { 
                data: 'id', 
                name: 'id',
                searchable: true,
                className: 'text-center'
            },
            { 
                data: 'image', 
                name: 'image', 
                orderable: false, 
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return data || '<span class="text-muted">لا توجد صورة</span>';
                    }
                    return data;
                }
            },
            { 
                data: 'operation', 
                name: 'operation', 
                orderable: false, 
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return data || '';
                    }
                    return data;
                }
            }
        ]
    });

    // Delete Offer
    $('#offersTable').on('click', '.delete_btn', function() {
        const offerId = $(this).data('id');

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
                $.ajax({
                    url: "<?php echo e(route('dashboard.offers.destroy')); ?>",
                    type: "POST",
                    data: {
                        _token: "<?php echo e(csrf_token()); ?>",
                        id: offerId
                    },
                    success: function(response) {
                        Swal.fire('تم الحذف!', response.success, 'success');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('خطأ!', xhr.responseJSON?.error || 'حدث خطأ ما!', 'error');
                    }
                });
            }
        });
    });
    
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/offers/index.blade.php ENDPATH**/ ?>