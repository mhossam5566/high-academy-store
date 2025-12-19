
<?php $__env->startSection('title'); ?>
    كوبونات
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">الكوبونات</h6>
                <div class="dropdown morphing scale-left">
                    <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="ملء الشاشة"><i
                            class="icon-size-fullscreen"></i></a>
                    <a href="#" class="more-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i
                            class="fa fa-ellipsis-h"></i></a>
                    <ul class="dropdown-menu shadow border-0 p-2">
                        <li><a class="dropdown-item" href="#">معلومات الملف</a></li>
                        <li><a class="dropdown-item" href="#">نسخ إلى</a></li>
                        <li><a class="dropdown-item" href="#">نقل إلى</a></li>
                        <li><a class="dropdown-item" href="#">إعادة تسمية</a></li>
                        <li><a class="dropdown-item" href="#">حظر</a></li>
                        <li><a class="dropdown-item" href="#">حذف</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover align-middle mb-0" id="couponsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>السعر</th>
                            <th>الصورة</th>
                            <th>الكوبونات المتاحة</th>
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
<?php $__env->startSection('js'); ?>
   <script>
       $(document).ready(function() {

    $('#couponsTable').DataTable({
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "الكل"]
        ],
        "paging": true,
        "pageLength": 10,
        "stateSave": true,
        "stateDuration": -1,
        "processing": true,
        "serverSide": true,
        'scrollX': true,
        "sort": false,
        "ajax": {
            "url": "<?php echo e(route('dashboard.coupons.datatable')); ?>",
            "type": "GET"
        },
        "columns": [
            {
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'price',
                name: 'price'
            },
            {
                data: 'image',
                name: 'image'
            },
            {
                data: 'count',
                name: 'count'
            },
            {
                data: 'operation',
                name: 'operation',
                orderable: false
            }
        ],

    });
});

$(document).on('click', '.delete_btn', function(e) {
    e.preventDefault();

    var coupon_id = $(this).attr('coupon_id');

    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: "لا يمكن التراجع عن هذا الإجراء!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم، احذف!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo e(route('dashboard.coupons.destroy')); ?>',
                type: "POST",
                dataType: "json",
                data: {
                    '_token': "<?php echo e(csrf_token()); ?>",
                    'id': coupon_id
                },
                success: function(data) {
                    Swal.fire({
                        title: 'تم الحذف!',
                        text: 'تم حذف الكوبون بنجاح.',
                        icon: 'success',
                        confirmButtonText: 'موافق'
                    }).then(() => {
                        // إعادة تحميل الصفحة بعد النقر على "موافق"
                        $('#couponsTable').DataTable().ajax.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire('خطأ!', 'حدث خطأ أثناء الحذف.', 'error');
                }
            });
        }
    });
});


   </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/admin/coupon/index.blade.php ENDPATH**/ ?>