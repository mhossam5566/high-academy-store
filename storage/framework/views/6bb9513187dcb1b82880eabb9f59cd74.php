<?php $__env->startSection('title'); ?>
    Teachers
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Patients Status</h6>
                <div class="dropdown morphing scale-left">
                    <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                            class="icon-size-fullscreen"></i></a>
                    <a href="#" class="more-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i
                            class="fa fa-ellipsis-h"></i></a>
                    <ul class="dropdown-menu shadow border-0 p-2">
                        <li><a class="dropdown-item" href="#">File Info</a></li>
                        <li><a class="dropdown-item" href="#">Copy to</a></li>
                        <li><a class="dropdown-item" href="#">Move to</a></li>
                        <li><a class="dropdown-item" href="#">Rename</a></li>
                        <li><a class="dropdown-item" href="#">Block</a></li>
                        <li><a class="dropdown-item" href="#">Delete</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover align-middle mb-0" id="myTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الصوره</th>
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

            $('#myTable').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],
                "processing": true,
                "serverSide": true,
                "paging": true,
                "pageLength": 10,
                "stateSave": true,
                "stateDuration": -1,
                'scrollX': true,

                "sort": false,
                "ajax": {
                    "url": "<?php echo e(route('dashboard.brand.datatable')); ?>",
                    "type": "GET"
                },
                "columns": [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'photo',
                        name: 'photo'
                    },
                    {
                        data: 'operation',
                        name: 'operation',
                        orderable: false
                    }
                ],

            });
        })
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete_btn', function(e) {
                e.preventDefault();
                var brand_id = $(this).attr('brand_id');
                $.ajax({
                    url: '<?php echo e(route('dashboard.teachers.destroy')); ?>',
                    type: "POST",
                    dataType: "json",
                    data: {
                        '_token': "<?php echo e(csrf_token()); ?>",
                        'id': brand_id
                    },
                    success: function(data) {
                        $('.brandrow' + data.id).remove();
                        Swal.fire('Data has been Deletd successfully', '', 'success');
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/admin/Brand/index.blade.php ENDPATH**/ ?>