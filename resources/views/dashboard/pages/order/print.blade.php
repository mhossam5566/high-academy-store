@extends('dashboard.layouts.layoutMaster')

@section('title', 'طباعة الطلبات')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet"
        href="{{ asset('dashboard/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(67, 89, 113, 0.04);
        }
        .order-select-chk, #checkAll {
            cursor: pointer;
            width: 19px;
            height: 19px;
        }
        .toolbar-badge {
            font-size: 0.95rem;
            padding: 0.5rem 0.85rem;
        }
        .shipping-card {
            cursor: pointer;
            user-select: none;
            border-radius: 0.625rem;
            transition: all 0.2s ease-in-out;
            border-width: 2px !important;
        }
        .shipping-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 .25rem 1rem rgba(115, 103, 240, 0.15) !important;
        }
        .shipping-card.active {
            border-color: #7367f0 !important;
            background-color: rgba(115, 103, 240, 0.05);
        }
        .shipping-card.active .shipping-icon-box {
            background-color: #7367f0 !important;
            color: #fff !important;
        }
        .shipping-card.active .shipping-title {
            color: #7367f0;
        }
        .shipping-card:not(.active) {
            border-color: #dbdade !important;
            background-color: #fafafb;
            opacity: 0.65;
        }
        .shipping-card:not(.active) .shipping-icon-box {
            background-color: #ececec !important;
            color: #a8aaae !important;
        }
        .shipping-card:not(.active) .shipping-title {
            color: #5d596c;
        }
        .shipping-card:not(.active) .shipping-count-badge {
            background-color: #ececec !important;
            color: #a8aaae !important;
        }
    </style>
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">لوحة التحكم / <a href="{{ route('dashboard.orders') }}" class="text-muted">الطلبات</a> /</span> طباعة الطلبات
        </h4>
        <div>
            <span class="badge bg-label-info toolbar-badge">
                <i class="ti ti-printer me-1"></i>الطلبات الناجحة فقط
            </span>
        </div>
    </div>

    <!-- Shipping Methods Cards (Flex Selection) -->
    <div class="mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
            <h6 class="mb-0 fw-bold d-flex align-items-center text-dark">
                <i class="ti ti-truck-delivery me-2 text-primary fs-4"></i>وسائل الشحن
                <span class="text-muted fw-normal ms-2 fs-7">(انقر على الوسيلة لتحديدها أو إلغاء تحديدها)</span>
            </h6>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-xs btn-outline-primary" id="btnSelectAllShipping">
                    <i class="ti ti-check-all me-1"></i>تحديد الكل
                </button>
                <button type="button" class="btn btn-xs btn-outline-secondary" id="btnUnselectAllShipping">
                    <i class="ti ti-x me-1"></i>إلغاء تحديد الكل
                </button>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-3 align-items-stretch" id="shippingCardsContainer">
            @foreach ($shippingMethods as $method)
                @php
                    $icon = 'ti-truck';
                    $typeLabel = 'شحن عام';
                    if ($method->type === 'post') {
                        $icon = 'ti-mailbox';
                        $typeLabel = 'مكتب بريد';
                    } elseif ($method->type === 'home') {
                        $icon = 'ti-home';
                        $typeLabel = 'توصيل لباب البيت';
                    } elseif ($method->type === 'branch') {
                        $icon = 'ti-building-store';
                        $typeLabel = 'استلام من الفرع';
                    }
                @endphp
                <div class="shipping-card card flex-fill border border-2 active shadow-none mb-0"
                    data-id="{{ $method->id }}"
                    style="min-width: 220px;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="avatar avatar-md rounded p-2 d-flex align-items-center justify-content-center shipping-icon-box shadow-xs"
                                style="transition: all 0.2s ease;">
                                <i class="ti {{ $icon }} fs-3"></i>
                            </div>
                            <div class="form-check m-0 p-0">
                                <input class="form-check-input shipping-method-chk"
                                    type="checkbox"
                                    value="{{ $method->id }}"
                                    id="ship_method_{{ $method->id }}"
                                    checked
                                    style="width: 20px; height: 20px; cursor: pointer; margin: 0;">
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold shipping-title" style="transition: color 0.2s ease;">{{ $method->name }}</h6>
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">{{ $typeLabel }}</small>
                                @if(isset($method->orders_count))
                                    <span class="badge rounded-pill shipping-count-badge bg-label-primary">
                                        {{ $method->orders_count }} طلب
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Filters & Actions Card -->
    <div class="card mb-4">
        <div class="card-header border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="card-title mb-0 d-flex align-items-center">
                <i class="ti ti-adjustments-horizontal me-2 text-primary"></i>فلاتر وعمليات الطباعة
            </h5>
            <div class="d-flex align-items-center gap-2">
                <span id="selectedCountBadge" class="badge bg-label-secondary toolbar-badge">
                    تم تحديد <strong id="selectedCountText">0</strong> طلب
                </span>
            </div>
        </div>
        <div class="card-body pt-3">
            <!-- Filter Options Row -->
            <div class="row g-3 mb-3">
                <!-- PDF Status Filter -->
                <div class="col-md-4 col-sm-6">
                    <label class="form-label fw-semibold">حالة طباعة PDF</label>
                    <select id="pdfFilter" class="form-select">
                        <option value="all">الكل</option>
                        <option value="1">تمت الطباعة (نعم)</option>
                        <option value="0">لم تطبع بعد (لا)</option>
                    </select>
                </div>

                <!-- Excel Status Filter -->
                <div class="col-md-4 col-sm-6">
                    <label class="form-label fw-semibold">حالة تصدير Excel</label>
                    <select id="excelFilter" class="form-select">
                        <option value="all">الكل</option>
                        <option value="1">تم التصدير (نعم)</option>
                        <option value="0">لم تصدر بعد (لا)</option>
                    </select>
                </div>

                <!-- Completed / Cleared Filter -->
                <div class="col-md-4 col-sm-6">
                    <label class="form-label fw-semibold">عرض السجلات</label>
                    <select id="completedFilter" class="form-select">
                        <option value="active" selected>الطلبات قيد الطباعة (غير المفرغة)</option>
                        <option value="completed">الطلبات المنتهية (المفرغة)</option>
                        <option value="all">كل الطلبات</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons Row -->
            <div class="d-flex flex-wrap align-items-center gap-2 pt-2 border-top">
                <button type="button" id="btnExportPdf" class="btn btn-success d-inline-flex align-items-center gap-1" disabled>
                    <i class="ti ti-file-text fs-5"></i>
                    <span>تصدير الطلبات الناجحة بالتفصيل (PDF)</span>
                </button>

                <button type="button" id="btnExportExcel" class="btn btn-warning d-inline-flex align-items-center gap-1" disabled>
                    <i class="ti ti-file-spreadsheet fs-5"></i>
                    <span>تصدير Excel</span>
                </button>

                <button type="button" id="btnClearOrders" class="btn btn-danger d-inline-flex align-items-center gap-1" disabled>
                    <i class="ti ti-archive fs-5"></i>
                    <span>تفريغ النتائج المحددة</span>
                </button>

                <button type="button" id="btnRestoreOrders" class="btn btn-outline-secondary d-none align-items-center gap-1" disabled>
                    <i class="ti ti-rotate-clockwise fs-5"></i>
                    <span>استرجاع إلى جدول الطباعة</span>
                </button>

                <button type="button" id="btnClearSelection" class="btn btn-label-secondary ms-auto d-none">
                    <i class="ti ti-x me-1"></i>إلغاء التحديد
                </button>
            </div>
        </div>
    </div>

    <!-- Orders Table Card -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="table table-hover" id="printOrdersTable" dir="rtl">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 40px;">
                            <div class="form-check d-flex justify-content-center m-0">
                                <input type="checkbox" id="checkAll" class="form-check-input" title="تحديد الكل في هذه الصفحة">
                            </div>
                        </th>
                        <th>#</th>
                        <th>اسم العميل</th>
                        <th>رقم الهاتف</th>
                        <th>العنوان / المحافظة</th>
                        <th>طريقة الشحن</th>
                        <th>إجمالي الطلب</th>
                        <th>التاريخ</th>
                        <th class="text-center">Excel</th>
                        <th class="text-center">PDF</th>
                        <th class="text-center">معاينة</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Hidden Form for Downloading PDF / Excel -->
    <form id="exportHiddenForm" method="POST" action="" style="display: none;">
        @csrf
        <input type="hidden" name="order_ids" id="exportHiddenOrderIds" value="">
    </form>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            let selectedIds = new Set();

            const table = $('#printOrdersTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                lengthMenu: [
                    [10, 25, 50, 100, 200, -1],
                    [10, 25, 50, 100, 200, "الكل"]
                ],
                pageLength: 25,
                ajax: {
                    url: "{{ route('dashboard.orders.print.datatable') }}",
                    data: function(d) {
                        let selectedShipping = [];
                        $('.shipping-method-chk:checked').each(function() {
                            selectedShipping.push($(this).val());
                        });
                        d.shipping_methods = selectedShipping.length > 0 ? selectedShipping.join(',') : 'none';
                        d.printed_pdf = $('#pdfFilter').val();
                        d.printed_excel = $('#excelFilter').val();
                        d.completed_status = $('#completedFilter').val();
                    }
                },
                columns: [
                    {
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'id',
                        name: 'id',
                        className: 'fw-bold'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'shipping_method',
                        name: 'shipping_method',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'total',
                        name: 'total',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'date',
                        name: 'date',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'printed_excel',
                        name: 'printed_excel',
                        className: 'text-center',
                        searchable: false
                    },
                    {
                        data: 'printed_pdf',
                        name: 'printed_pdf',
                        className: 'text-center',
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                language: {
                    processing: "جارٍ التحميل...",
                    search: "بحث:",
                    lengthMenu: "عرض _MENU_ سجل",
                    info: "عرض _START_ إلى _END_ من أصل _TOTAL_ طلب",
                    infoEmpty: "عرض 0 إلى 0 من أصل 0 طلب",
                    infoFiltered: "(منتقاة من مجموع _MAX_ طلب)",
                    zeroRecords: "لم يعثر على طلبات متطابقة",
                    emptyTable: "لا توجد طلبات متاحة",
                    paginate: {
                        first: "الأول",
                        previous: "السابق",
                        next: "التالي",
                        last: "الأخير"
                    }
                },
                drawCallback: function() {
                    // Update row checkboxes state from selectedIds set
                    let allCheckedOnPage = true;
                    let hasRows = false;

                    $('.order-select-chk').each(function() {
                        hasRows = true;
                        let id = $(this).val();
                        if (selectedIds.has(id)) {
                            $(this).prop('checked', true);
                            $(this).closest('tr').addClass('table-active');
                        } else {
                            $(this).prop('checked', false);
                            $(this).closest('tr').removeClass('table-active');
                            allCheckedOnPage = false;
                        }
                    });

                    $('#checkAll').prop('checked', hasRows && allCheckedOnPage);
                    updateUIButtons();
                }
            });

            // Update UI elements based on selected count
            function updateUIButtons() {
                let count = selectedIds.size;
                $('#selectedCountText').text(count);

                if (count > 0) {
                    $('#selectedCountBadge').removeClass('bg-label-secondary').addClass('bg-primary text-white');
                    $('#btnExportPdf, #btnExportExcel, #btnClearOrders, #btnRestoreOrders').prop('disabled', false);
                    $('#btnClearSelection').removeClass('d-none');
                } else {
                    $('#selectedCountBadge').removeClass('bg-primary text-white').addClass('bg-label-secondary');
                    $('#btnExportPdf, #btnExportExcel, #btnClearOrders, #btnRestoreOrders').prop('disabled', true);
                    $('#btnClearSelection').addClass('d-none');
                }

                // Show restore button only when completed/archived view is selected
                let filterStatus = $('#completedFilter').val();
                if (filterStatus === 'completed') {
                    $('#btnRestoreOrders').removeClass('d-none');
                    $('#btnClearOrders').addClass('d-none');
                } else {
                    $('#btnRestoreOrders').addClass('d-none');
                    $('#btnClearOrders').removeClass('d-none');
                }
            }

            // Checkbox individual change
            $(document).on('change', '.order-select-chk', function() {
                let id = $(this).val();
                if ($(this).is(':checked')) {
                    selectedIds.add(id);
                    $(this).closest('tr').addClass('table-active');
                } else {
                    selectedIds.delete(id);
                    $(this).closest('tr').removeClass('table-active');
                }

                // Check if all on current page are checked
                let allChecked = true;
                $('.order-select-chk').each(function() {
                    if (!$(this).is(':checked')) {
                        allChecked = false;
                        return false;
                    }
                });
                $('#checkAll').prop('checked', allChecked);

                updateUIButtons();
            });

            // Check All change
            $('#checkAll').on('change', function() {
                let isChecked = $(this).is(':checked');
                $('.order-select-chk').each(function() {
                    let id = $(this).val();
                    $(this).prop('checked', isChecked);
                    if (isChecked) {
                        selectedIds.add(id);
                        $(this).closest('tr').addClass('table-active');
                    } else {
                        selectedIds.delete(id);
                        $(this).closest('tr').removeClass('table-active');
                    }
                });
                updateUIButtons();
            });

            // Clear selection button
            $('#btnClearSelection').on('click', function() {
                selectedIds.clear();
                $('#checkAll').prop('checked', false);
                $('.order-select-chk').prop('checked', false).closest('tr').removeClass('table-active');
                updateUIButtons();
            });

            // Toggle shipping card on card click
            $(document).on('click', '.shipping-card', function(e) {
                if ($(e.target).closest('.shipping-method-chk').length > 0) {
                    return;
                }
                let chk = $(this).find('.shipping-method-chk');
                chk.prop('checked', !chk.prop('checked')).trigger('change');
            });

            // Shipping method checkbox change
            $(document).on('change', '.shipping-method-chk', function() {
                let card = $(this).closest('.shipping-card');
                if ($(this).is(':checked')) {
                    card.addClass('active');
                } else {
                    card.removeClass('active');
                }
                table.ajax.reload();
            });

            // Select all shipping methods
            $('#btnSelectAllShipping').on('click', function() {
                $('.shipping-method-chk').prop('checked', true);
                $('.shipping-card').addClass('active');
                table.ajax.reload();
            });

            // Unselect all shipping methods
            $('#btnUnselectAllShipping').on('click', function() {
                $('.shipping-method-chk').prop('checked', false);
                $('.shipping-card').removeClass('active');
                table.ajax.reload();
            });

            // Filter changes
            $('#pdfFilter, #excelFilter, #completedFilter').on('change', function() {
                table.ajax.reload();
            });

            // Action: Export PDF
            $('#btnExportPdf').on('click', function() {
                let idsArray = Array.from(selectedIds);
                if (idsArray.length === 0) {
                    Swal.fire({
                        title: 'تنبيه',
                        text: 'يرجى تحديد طلب واحد على الأقل للتصدير',
                        icon: 'warning',
                        confirmButtonText: 'حسنًا'
                    });
                    return;
                }

                $('#exportHiddenForm').attr('action', "{{ route('dashboard.orders.print.export.pdf') }}");
                $('#exportHiddenOrderIds').val(idsArray.join(','));
                $('#exportHiddenForm').submit();

                // Reload table after brief delay so updated "printed_pdf" state is immediately shown
                setTimeout(function() {
                    table.ajax.reload(null, false);
                }, 1500);
            });

            // Action: Export Excel
            $('#btnExportExcel').on('click', function() {
                let idsArray = Array.from(selectedIds);
                if (idsArray.length === 0) {
                    Swal.fire({
                        title: 'تنبيه',
                        text: 'يرجى تحديد طلب واحد على الأقل للتصدير',
                        icon: 'warning',
                        confirmButtonText: 'حسنًا'
                    });
                    return;
                }

                $('#exportHiddenForm').attr('action', "{{ route('dashboard.orders.print.export.excel') }}");
                $('#exportHiddenOrderIds').val(idsArray.join(','));
                $('#exportHiddenForm').submit();

                // Reload table after brief delay so updated "printed_excel" state is immediately shown
                setTimeout(function() {
                    table.ajax.reload(null, false);
                }, 1500);
            });

            // Action: Clear / Dismiss Orders
            $('#btnClearOrders').on('click', function() {
                let idsArray = Array.from(selectedIds);
                if (idsArray.length === 0) {
                    Swal.fire({
                        title: 'تنبيه',
                        text: 'يرجى تحديد طلب واحد على الأقل لتفريغ النتائج',
                        icon: 'warning',
                        confirmButtonText: 'حسنًا'
                    });
                    return;
                }

                Swal.fire({
                    title: 'تفريغ النتائج المحددة',
                    text: 'هل أنت متأكد من تفريغ ' + idsArray.length + ' طلب محدد؟ ستختفي هذه الطلبات من جدول الطباعة الحالي.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#8592a3',
                    confirmButtonText: 'نعم، تفريغ واختفاء من الجدول',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('dashboard.orders.print.clear') }}",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                order_ids: idsArray
                            },
                            beforeSend: function() {
                                Swal.showLoading();
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'تم بنجاح!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'حسنًا'
                                });
                                selectedIds.clear();
                                $('#checkAll').prop('checked', false);
                                updateUIButtons();
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                let msg = 'حدث خطأ أثناء تفريغ النتائج';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    title: 'خطأ',
                                    text: msg,
                                    icon: 'error',
                                    confirmButtonText: 'حسنًا'
                                });
                            }
                        });
                    }
                });
            });

            // Action: Restore Orders (when in completed view)
            $('#btnRestoreOrders').on('click', function() {
                let idsArray = Array.from(selectedIds);
                if (idsArray.length === 0) {
                    Swal.fire({
                        title: 'تنبيه',
                        text: 'يرجى تحديد طلب واحد على الأقل للاسترجاع',
                        icon: 'warning',
                        confirmButtonText: 'حسنًا'
                    });
                    return;
                }

                Swal.fire({
                    title: 'استرجاع الطلبات',
                    text: 'هل أنت متأكد من استرجاع ' + idsArray.length + ' طلب إلى جدول الطباعة؟',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#8592a3',
                    confirmButtonText: 'نعم، استرجاع',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('dashboard.orders.print.restore') }}",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                order_ids: idsArray
                            },
                            beforeSend: function() {
                                Swal.showLoading();
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'تم الاسترجاع!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'حسنًا'
                                });
                                selectedIds.clear();
                                $('#checkAll').prop('checked', false);
                                updateUIButtons();
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                let msg = 'حدث خطأ أثناء الاسترجاع';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    title: 'خطأ',
                                    text: msg,
                                    icon: 'error',
                                    confirmButtonText: 'حسنًا'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
