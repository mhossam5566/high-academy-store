<?php $__env->startSection('title', 'إدارة المدن'); ?>

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
                <i class="ti ti-map-pin me-2"></i>إدارة المدن
                <?php if($governorate): ?>
                    <span class="badge bg-label-primary"><?php echo e($governorate->name_ar); ?></span>
                <?php endif; ?>
            </h4>
            <div class="d-flex gap-2">
                <button class="btn btn-success" onclick="importFromJson()">
                    <i class="ti ti-download me-1"></i>استيراد من JSON
                </button>
                <a href="<?php echo e(route('dashboard.cities.create', $governorate ? ['governorate' => $governorate->id] : [])); ?>" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>إضافة مدينة
                </a>
            </div>
        </div>

        <!-- Cities Table Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>قائمة المدن
                </h5>
                <?php if(!$governorate): ?>
                    <div class="d-flex align-items-center gap-2">
                        <label class="mb-0">تصفية حسب المحافظة:</label>
                        <select id="governorateFilter" class="form-select" style="width: 200px;">
                            <option value="">جميع المحافظات</option>
                            <?php $__currentLoopData = $governorates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($gov->id); ?>"><?php echo e($gov->name_ar); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="citiesTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم بالعربية</th>
                                <th>الاسم بالإنجليزية</th>
                                <?php if(!$governorate): ?>
                                    <th>المحافظة</th>
                                <?php endif; ?>
                                <th>الحالة</th>
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
            }
        ];

        <?php if(!$governorate): ?>
            columns.push({ 
                data: 'governorate', 
                name: 'governorate',
                render: function(data, type, row) {
                    return '<span class="badge bg-label-info">' + data + '</span>';
                }
            });
        <?php endif; ?>

        columns.push(
            { 
                data: 'status', 
                name: 'status',
                className: 'text-center',
                render: function(data, type, row) {
                    if (data == 1 || data === true || data === 'نشط') {
                        return '<span class="badge bg-success">نشطة</span>';
                    } else {
                        return '<span class="badge bg-danger">غير نشطة</span>';
                    }
                }
            },
            { 
                data: 'actions', 
                name: 'actions', 
                orderable: false, 
                searchable: false,
                className: 'text-center'
            }
        );

        var table = $('#citiesTable').DataTable({
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
                "url": "<?php echo e(route('dashboard.cities.datatable')); ?>",
                "type": "GET",
                "data": function(d) {
                    <?php if($governorate): ?>
                        d.governorate = <?php echo e($governorate->id); ?>;
                    <?php else: ?>
                        d.governorate = $('#governorateFilter').val();
                    <?php endif; ?>
                }
            },
            "columns": columns
        });

        <?php if(!$governorate): ?>
            $('#governorateFilter').on('change', function() {
                table.ajax.reload();
            });
        <?php endif; ?>
    });

    function deleteCity(id) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'لن تتمكن من استرجاع هذه المدينة بعد الحذف!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo e(route("dashboard.cities.destroy", ":id")); ?>'.replace(':id', id),
                    type: 'DELETE',
                    data: { _token: '<?php echo e(csrf_token()); ?>' },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('تم الحذف!', response.message, 'success');
                            $('#citiesTable').DataTable().ajax.reload();
                        } else {
                            Swal.fire('خطأ!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('خطأ!', 'حدث خطأ أثناء حذف المدينة', 'error');
                    }
                });
            }
        });
    }

    function importFromJson() {
        Swal.fire({
            title: 'استيراد المدن',
            text: 'هل تريد استيراد المدن من ملف JSON؟',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'نعم، استورد!',
            cancelButtonText: 'إلغاء',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: '<?php echo e(route("dashboard.cities.import-json")); ?>',
                    type: 'POST',
                    data: { _token: '<?php echo e(csrf_token()); ?>' }
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value.success) {
                    Swal.fire('تم الاستيراد!', result.value.message, 'success');
                    $('#citiesTable').DataTable().ajax.reload();
                } else {
                    Swal.fire('خطأ!', result.value.message, 'error');
                }
            }
        }).catch(error => {
            Swal.fire('خطأ!', 'حدث خطأ أثناء استيراد المدن', 'error');
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/cities/index.blade.php ENDPATH**/ ?>