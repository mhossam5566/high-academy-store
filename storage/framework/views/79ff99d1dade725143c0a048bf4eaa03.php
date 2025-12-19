

<?php $__env->startSection('title', 'الأسئلة الشائعة'); ?>

<?php $__env->startSection('vendor-style'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">الأسئلة الشائعة</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard.index')); ?>">لوحة التحكم</a></li>
                <li class="breadcrumb-item active">الأسئلة الشائعة</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <form action="<?php echo e(route('dashboard.faqs.cleanup-duplicates')); ?>" method="POST" class="d-inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-warning" onclick="return confirm('هل تريد تنظيف الترتيبات المكررة؟')">
                <i class="ti ti-broom me-1"></i>تنظيف المكررات
            </button>
        </form>
        <a href="<?php echo e(route('dashboard.faqs.create')); ?>" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i>إضافة سؤال جديد
        </a>
    </div>
</div>

<!-- FAQ Table Card -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="ti ti-help-circle me-2"></i>قائمة الأسئلة الشائعة
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="faqsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>السؤال</th>
                        <th>الإجابة</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
<script>
    $(document).ready(function() {
        $('#faqsTable').DataTable({
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "الكل"]
            ],
            "paging": true,
            "pageLength": 10,
            "stateSave": true,
            "stateDuration": -1,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "sort": false,
            "responsive": true,
            "ajax": {
                "url": "<?php echo e(route('dashboard.faqs.datatable')); ?>",
                "type": "GET"
            },
            "columns": [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'question',
                    name: 'question'
                },
                {
                    data: 'answer',
                    name: 'answer'
                },
                {
                    data: 'display_order',
                    name: 'display_order'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
            }
        });
    });

    function deleteFaq(id) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'لن تتمكن من استرجاع هذا السؤال والإجابة بعد الحذف!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo e(route("dashboard.faqs.destroy", ":id")); ?>'.replace(':id', id),
                    type: 'DELETE',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'تم الحذف!',
                                response.message,
                                'success'
                            );
                            $('#faqsTable').DataTable().ajax.reload();
                        } else {
                            Swal.fire(
                                'خطأ!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'خطأ!',
                            'حدث خطأ أثناء حذف السؤال والإجابة',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/faq/index.blade.php ENDPATH**/ ?>