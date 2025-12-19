<?php $__env->startSection('title', 'الطلبات'); ?>

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
            <span class="text-muted fw-light">لوحة التحكم /</span> الطلبات
        </h4>
    </div>

    <!-- Filters Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-filter me-2"></i>فلترة الطلبات
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- State Filter -->
                <div class="col-md-6">
                    <label class="form-label">حالة الطلب</label>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100" id="stateFilterBtn" data-bs-toggle="dropdown">
                            <span id="stateFilterLabel">كل الحالات</span>
                        </button>
                        <ul class="dropdown-menu w-100">
                            <li><a href="#" class="dropdown-item state-filter-item" data-value="">كل الحالات</a></li>
                            <li><a href="#" class="dropdown-item state-filter-item" data-value="new">طلب جديد</a></li>
                            <li><a href="#" class="dropdown-item state-filter-item" data-value="reserved">طلب محجوز</a></li>
                            <li><a href="#" class="dropdown-item state-filter-item" data-value="success">طلب ناجح</a></li>
                            <li><a href="#" class="dropdown-item state-filter-item" data-value="pending">طلب معلق</a></li>
                            <li><a href="#" class="dropdown-item state-filter-item" data-value="cancelled">تم الإلغاء</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Shipping Method Filter -->
                <div class="col-md-6">
                    <label class="form-label">طريقة الشحن</label>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100" id="shippingFilterBtn" data-bs-toggle="dropdown">
                            <span id="shippingFilterLabel">كل طرق الشحن</span>
                        </button>
                        <ul class="dropdown-menu w-100">
                            <li>
                                <a href="#" class="dropdown-item shipping-filter-item" data-value="">
                                    كل طرق الشحن
                                </a>
                            </li>
                            <?php $__currentLoopData = $shippingMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a href="#" class="dropdown-item shipping-filter-item" data-value="<?php echo e($method->id); ?>">
                                        <?php echo e($method->name); ?>

                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Tools Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-file-export me-2"></i>أدوات التصدير
            </h5>
        </div>
        <div class="card-body">
            <!-- Order Type Selection -->
            <div class="row g-3 mb-3">
                <div class="col-md-12">
                    <label class="form-label fw-bold">نوع الطلب</label>
                    <select id="orderStatus" class="form-select">
                        <option value="" disabled selected>اختر نوع الطلب</option>
                        <option value="success">الطلبات الناجحة</option>
                        <option value="reserved">الطلبات المحجوزة</option>
                        <option value="pending">الطلبات المعلقة</option>
                    </select>
                </div>
            </div>

            <div class="row g-3">
                <!-- Manual Export -->
                <div class="col-md-6">
                    <label class="form-label">تصدير الطلبات يدوياً</label>
                    <div class="input-group">
                        <input type="number" id="orderLimit" class="form-control" placeholder="عدد الطلبات" min="1" oninput="validateInput(this)">
                        <button class="btn btn-success" onclick="exportOrders()">
                            <i class="ti ti-download me-1"></i>تصدير
                        </button>
                    </div>
                </div>

                <!-- Export with Details -->
                <div class="col-md-6">
                    <label class="form-label">تصدير الطلبات بالتفاصيل</label>
                    <div class="input-group">
                        <input type="number" id="orderSuccessLimit" class="form-control" placeholder="عدد الطلبات" min="1" oninput="validateInput(this)">
                        <button class="btn btn-success" onclick="exportOrdersSuccess()">
                            <i class="ti ti-download me-1"></i>تصدير
                        </button>
                    </div>
                </div>

                <!-- Export Grouped Orders -->
                <div class="col-md-6">
                    <label class="form-label">تصدير الطلبات الناجحة بالتفصيل</label>
                    <div class="input-group">
                        <input type="number" id="orderGroupedLimit" class="form-control" placeholder="عدد الطلبات" min="1" oninput="validateInput(this)">
                        <button class="btn btn-info" onclick="exportGroupedOrders()">
                            <i class="ti ti-download me-1"></i>تصدير
                        </button>
                    </div>
                </div>

                <!-- Export Branch Orders -->
                <div class="col-md-6">
                    <label class="form-label">تصدير طلبات الفروع</label>
                    <div class="input-group">
                        <input type="number" id="orderBranchLimit" class="form-control" placeholder="عدد الطلبات" min="1" oninput="validateInput(this)">
                        <button class="btn btn-info" onclick="exportBranchOrders()">
                            <i class="ti ti-download me-1"></i>تصدير
                        </button>
                    </div>
                </div>
            </div>

            <!-- Convert Reserved to Success -->
            <div class="row g-3 mt-2">
                <div class="col-md-12">
                    <button onclick="confirmUpdateAllReversed()" class="btn btn-danger w-100">
                        <i class="ti ti-refresh me-1"></i>تحويل جميع الطلبات المحجوزة لحالة ناجح
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table Card -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="table table-hover" id="myTable" dir="rtl">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الرقم</th>
                        <th>عنوان الشحن</th>
                        <th>نوع الشحن</th>
                        <th>إجمالي المدفوع</th>
                        <th>وسيلة الدفع</th>
                        <th>رقم حساب الدفع</th>
                        <th>إيصال الدفع</th>
                        <th>حالة العملية</th>
                        <th>تغيير الحالة</th>
                        <th>تعديل الطلب</th>
                        <th>التفاصيل</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
    <script>
        function confirmUpdateAllReversed() {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم تحويل جميع الطلبات المحجوزة إلى حالة ناجح. هل تريد المتابعة؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، قم بالتحويل',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?php echo e(route('dashboard.update_all_reversed_order')); ?>";
                }
            });
        }

        $(document).ready(function() {
            let stateFilterValue = '';
            let shippingFilterValue = '';

            const table = $('#myTable').DataTable({
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
                ajax: {
                    url: "<?php echo e(route('dashboard.orders.datatable')); ?>",
                    data: d => {
                        d.state = stateFilterValue;
                        d.shipping = shippingFilterValue;
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'phone', name: 'mobile' },
                    { data: 'address', name: 'address' },
                    { data: 'shipping_method', name: 'shipping_method' },
                    { data: 'total', name: 'total' },
                    { data: 'method', name: 'method' },
                    { data: 'account', name: 'account' },
                    { data: 'image', name: 'image' },
                    { data: 'state', name: 'state' },
                    { data: 'change_status', name: 'change_status' },
                    { data: 'edit_order', name: 'edit_order' },
                    { data: 'details', name: 'details' },
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
                }
            });

            function updateMainExportLinks() {
                var info = table.page.info();
                var lengthMenuSetting = info.length > 0 ? info.length : 10;
                let baseExportUrl = "<?php echo e(route('dashboard.orders.export')); ?>";
                let baseSuccessExportUrl = "<?php echo e(route('dashboard.orders.export.success')); ?>";

                let params = new URLSearchParams();
                params.append('limit', lengthMenuSetting);

                if (stateFilterValue) {
                    params.append('status', stateFilterValue);
                }
                if (shippingFilterValue) {
                    params.append('shipping', shippingFilterValue);
                }

                $('#export').attr('href', baseExportUrl + '?' + params.toString());

                if (stateFilterValue) {
                    $('#successExport').attr('href', baseSuccessExportUrl + '?' + params.toString()).removeClass('disabled');
                } else {
                    $('#successExport').attr('href', '#').addClass('disabled');
                }
            }

            updateMainExportLinks();

            $('.state-filter-item').on('click', function(e) {
                e.preventDefault();
                stateFilterValue = $(this).data('value');
                $('#stateFilterLabel').text($(this).text());
                table.draw();
                updateMainExportLinks();
            });

            $('.shipping-filter-item').on('click', function(e) {
                e.preventDefault();
                shippingFilterValue = $(this).data('value');
                $('#shippingFilterLabel').text($(this).text());
                table.ajax.reload();
                updateMainExportLinks();
            });

            $('#myTable_length select').on('change', function() {
                setTimeout(updateMainExportLinks, 100);
            });

            window.getShippingFilterValue = () => shippingFilterValue;
        });

        function handleStatusChange(selectElement) {
            let selectedValue = selectElement.value;
            if (selectedValue.startsWith("http")) {
                window.location.href = selectedValue;
            }
        }

        function exportOrders() {
            let limit = document.getElementById("orderLimit").value || 10;
            let status = $('#orderStatus').val();

            if (!status) {
                Swal.fire({
                    title: 'خطأ',
                    text: 'يرجى تحديد نوع الطلب أولاً.',
                    icon: 'error',
                    confirmButtonText: 'حسنًا'
                });
                return;
            }

            let url = new URL("<?php echo e(route('dashboard.orders.export')); ?>");
            url.searchParams.append('limit', limit);
            url.searchParams.append('status', status);

            window.location.href = url.toString();
            document.getElementById("orderLimit").value = '';
        }

        function exportOrdersSuccess() {
            let limit = document.getElementById("orderSuccessLimit").value || 10;
            let status = $('#orderStatus').val();

            if (!status) {
                Swal.fire({
                    title: 'خطأ',
                    text: 'يرجى تحديد نوع الطلب أولاً.',
                    icon: 'error',
                    confirmButtonText: 'حسنًا'
                });
                return;
            }

            let url = new URL("<?php echo e(route('dashboard.orders.export.success')); ?>");
            url.searchParams.append('limit', limit);
            url.searchParams.append('status', status);

            window.location.href = url.toString();
            document.getElementById("orderSuccessLimit").value = '';
        }

        function exportGroupedOrders() {
            let limit = document.getElementById("orderGroupedLimit").value || 10;
            let status = $('#orderStatus').val();

            if (!status) {
                Swal.fire({
                    title: 'خطأ',
                    text: 'يرجى تحديد نوع الطلب أولاً.',
                    icon: 'error',
                    confirmButtonText: 'حسنًا'
                });
                return;
            }

            let url = new URL("<?php echo e(route('dashboard.orders.export.grouped')); ?>");
            url.searchParams.append('limit', limit);
            url.searchParams.append('status', status);

            window.location.href = url.toString();
            document.getElementById("orderGroupedLimit").value = '';
        }

        function exportBranchOrders() {
            let limit = document.getElementById("orderBranchLimit").value || 10;
            let status = $('#orderStatus').val();

            if (!status) {
                Swal.fire({
                    title: 'خطأ',
                    text: 'يرجى تحديد نوع الطلب أولاً.',
                    icon: 'error',
                    confirmButtonText: 'حسنًا'
                });
                return;
            }

            let url = new URL("<?php echo e(route('dashboard.orders.export.branch')); ?>");
            url.searchParams.append('limit', limit);
            url.searchParams.append('status', status);

            window.location.href = url.toString();
            document.getElementById("orderBranchLimit").value = '';
        }

        function validateInput(input) {
            if (input.value < 1) {
                input.value = 1;
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/order/index.blade.php ENDPATH**/ ?>