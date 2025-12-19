<?php $__env->startSection('title', 'المدرسين'); ?>

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
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">لوحة التحكم /</span> المدرسين
        </h4>
        <a href="<?php echo e(route('dashboard.create.teachers')); ?>" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i>إضافة مدرس جديد
        </a>
    </div>

    <!-- Brands Table Card -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="table table-hover" id="brandsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الصورة</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
    <script>
        $(document).ready(function() {
            var table = $('#brandsTable').DataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]],
                ajax: {
                    url: '<?php echo e(route('dashboard.brand.datatable')); ?>',
                    type: 'GET'
                },
                columns: [
                    { data: 'id', name: 'id', width: '50px' },
                    { data: 'title', name: 'title' },
                    { data: 'photo', name: 'photo', orderable: false, searchable: false },
                    { data: 'operation', name: 'operation', orderable: false, searchable: false }
                ],
                order: [[0, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
                }
            });

            // Delete handler
            $(document).on('click', '.delete_btn', function(e) {
                e.preventDefault();
                var brand_id = $(this).attr('brand_id');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "لا يمكنك التراجع بعد الحذف!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذفه!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?php echo e(route('dashboard.teachers.destroy')); ?>',
                            type: "POST",
                            data: {
                                '_token': "<?php echo e(csrf_token()); ?>",
                                'id': brand_id
                            },
                            success: function(data) {
                                Swal.fire('تم الحذف!', 'تم حذف المدرس بنجاح', 'success');
                                table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                Swal.fire('خطأ!', 'لم يتم الحذف، يرجى المحاولة لاحقًا', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/Brand/index.blade.php ENDPATH**/ ?>