<?php $__env->startSection('title', 'المنتجات'); ?>

<?php $__env->startSection('vendor-style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dashboard/assets/vendor/libs/toastr/toastr.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')); ?>"></script>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js')); ?>"></script>
    <script src="<?php echo e(asset('dashboard/assets/vendor/libs/toastr/toastr.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">لوحة التحكم /</span> المنتجات
        </h4>
        <a href="<?php echo e(route('dashboard.create.product')); ?>" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i>إضافة منتج جديد
        </a>
    </div>

    <!-- Filters Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-filter me-2"></i>فلترة المنتجات
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">الاسم</label>
                    <input type="text" class="form-control" id="name_filter" placeholder="ابحث بالاسم">
                </div>
                <div class="col-md-3">
                    <label class="form-label">القسم الرئيسي</label>
                    <select class="form-select" id="main_category_filter">
                        <option value="">الكل</option>
                        <?php $__currentLoopData = \App\Models\MainCategory::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mainCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($mainCategory->name != 'اكواد مدرسين الاونلاين'): ?>
                                <option value="<?php echo e($mainCategory->id); ?>"><?php echo e($mainCategory->name); ?></option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">الحالة</label>
                    <select class="form-select" id="status_filter">
                        <option value="">الكل</option>
                        <option value="0">نشط</option>
                        <option value="1">غير نشط</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">الأكثر مبيعاً</label>
                    <select class="form-select" id="best_seller_filter">
                        <option value="">الكل</option>
                        <option value="1">نعم</option>
                        <option value="0">لا</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button class="btn btn-primary flex-grow-1" id="filter_btn">
                        <i class="ti ti-search me-1"></i>بحث
                    </button>
                    <button class="btn btn-label-secondary" id="reset_btn">
                        <i class="ti ti-refresh"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="table table-hover" id="products-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الصورة</th>
                        <th>الاسم</th>
                        <th>الاسم المختصر</th>
                        <th>السعر</th>
                        <th>الكمية</th>
                        <th>القسم</th>
                        <th>المادة</th>
                        <th>الصف</th>
                        <th>المدرس</th>
                        <th>الحالة</th>
                        <th>مبيع</th>
                        <th>متوفر</th>
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
            var table = $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]],
                ajax: {
                    url: '<?php echo e(route('dashboard.product.datatable')); ?>',
                    data: function(d) {
                        d.name = $('#name_filter').val();
                        d.main_category_id = $('#main_category_filter').val();
                        d.status = $('#status_filter').val();
                        d.best_seller = $('#best_seller_filter').val();
                    }
                },
                columns: [
                    { data: 'id', name: 'id', width: '50px' },
                    { data: 'photo', name: 'photo', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'short_name', name: 'short_name' },
                    { data: 'price', name: 'price' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'main_category_id', name: 'main_category_id' },
                    { data: 'category_id', name: 'category_id' },
                    { data: 'slider_id', name: 'slider_id' },
                    { data: 'brand_id', name: 'brand_id' },
                    { data: 'is_deleted', name: 'is_deleted' },
                    { data: 'best_seller', name: 'best_seller' },
                    { data: 'state', name: 'state' },
                    { data: 'operation', name: 'operation', orderable: false, searchable: false }
                ],
                order: [[0, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
                }
            });

            $('#filter_btn').on('click', function() {
                table.ajax.reload(null, false);
            });

            $('#reset_btn').on('click', function() {
                $('#name_filter').val('');
                $('#main_category_filter').val('');
                $('#status_filter').val('');
                $('#best_seller_filter').val('');
                table.ajax.reload();
            });

            // Toggle visibility handler
            $(document).on('click', '.toggle-visibility-btn', function(e) {
                e.preventDefault();
                var productId = $(this).data('id');
                var action = $(this).data('action');
                
                var title = action === 'hide' ? 'هل تريد إخفاء المنتج؟' : 'هل تريد إظهار المنتج؟';
                var confirmText = action === 'hide' ? 'نعم، أخفه' : 'نعم، أظهره';
                
                Swal.fire({
                    title: title,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: confirmText,
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?php echo e(route('dashboard.product.soft-delete')); ?>',
                            type: "POST",
                            data: {
                                '_token': "<?php echo e(csrf_token()); ?>",
                                'id': productId,
                                'action': action
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    table.ajax.reload(null, false);
                                } else {
                                    toastr.error('حدث خطأ');
                                }
                            },
                            error: function() {
                                toastr.error('حدث خطأ أثناء العملية');
                            }
                        });
                    }
                });
            });

            // Force delete handler
            $(document).on('click', '.force-delete-btn', function(e) {
                e.preventDefault();
                var productId = $(this).data('id');
                
                Swal.fire({
                    title: 'تحذير!',
                    text: "سيتم حذف المنتج نهائياً ولا يمكن استرجاعه",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'نعم، احذف نهائياً',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?php echo e(route('dashboard.product.force-delete')); ?>',
                            type: "POST",
                            data: {
                                '_token': "<?php echo e(csrf_token()); ?>",
                                'id': productId
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('تم الحذف!', response.message, 'success');
                                    table.ajax.reload(null, false);
                                } else {
                                    Swal.fire('خطأ!', 'حدث خطأ', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('خطأ!', 'حدث خطأ أثناء الحذف', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/Product/index.blade.php ENDPATH**/ ?>