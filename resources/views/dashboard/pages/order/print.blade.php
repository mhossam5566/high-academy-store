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
            border-radius: 0.5rem;
            transition: all 0.15s ease-in-out;
            border: 1.5px solid #d9dee3;
            background-color: #fff;
            padding: 0.45rem 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
        }
        .shipping-card:hover {
            border-color: #7367f0 !important;
            box-shadow: 0 2px 6px rgba(115, 103, 240, 0.2);
        }
        .shipping-card.active {
            border-color: #7367f0 !important;
            background-color: rgba(115, 103, 240, 0.08) !important;
        }
        .shipping-card.active .shipping-title {
            color: #7367f0 !important;
            font-weight: 600;
        }
        .shipping-card.active .shipping-icon {
            color: #7367f0 !important;
        }
        .shipping-card.active .shipping-count-badge {
            background-color: #7367f0 !important;
            color: #fff !important;
        }
        .shipping-card:not(.active) {
            border-color: #dbdade !important;
            background-color: #fafafb !important;
            opacity: 0.7;
        }
        .shipping-card:not(.active) .shipping-title {
            color: #6f6b7d !important;
        }
        .shipping-card:not(.active) .shipping-icon {
            color: #a8aaae !important;
        }
        .shipping-card:not(.active) .shipping-count-badge {
            background-color: #ececec !important;
            color: #8592a3 !important;
        }
        .shipping-card .shipping-method-chk {
            width: 17px;
            height: 17px;
            cursor: pointer;
            margin: 0;
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

    <!-- Shipping Methods Compact Filter (Flex Sized-to-Word Rectangles) -->
    <div class="card mb-4 p-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
            <span class="fw-bold text-dark d-flex align-items-center">
                <i class="ti ti-truck-delivery me-2 text-primary fs-5"></i>طرق الشحن:
                <small class="text-muted fw-normal ms-2">(انقر على الوسيلة لتحديدها أو استبعادها)</small>
            </span>
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn btn-xs btn-label-primary py-1 px-2" id="btnSelectAllShipping">
                    <i class="ti ti-check-all me-1"></i>تحديد الكل
                </button>
                <button type="button" class="btn btn-xs btn-label-secondary py-1 px-2" id="btnUnselectAllShipping">
                    <i class="ti ti-x me-1"></i>إلغاء التحديد
                </button>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 align-items-center" id="shippingCardsContainer">
            @foreach ($shippingMethods as $method)
                @php
                    $icon = 'ti-truck';
                    if ($method->type === 'post') {
                        $icon = 'ti-mailbox';
                    } elseif ($method->type === 'home') {
                        $icon = 'ti-home';
                    } elseif ($method->type === 'branch') {
                        $icon = 'ti-building-store';
                    }
                @endphp
                <div class="shipping-card active" data-id="{{ $method->id }}" title="{{ $method->name }}">
                    <input class="form-check-input shipping-method-chk"
                        type="checkbox"
                        value="{{ $method->id }}"
                        id="ship_method_{{ $method->id }}"
                        checked>
                    <i class="ti {{ $icon }} fs-5 shipping-icon"></i>
                    <span class="shipping-title text-nowrap">{{ $method->name }}</span>
                    @if(isset($method->orders_count))
                        <span class="badge rounded-pill shipping-count-badge">
                            {{ $method->orders_count }}
                        </span>
                    @endif
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
            let isSelectAllMode = false;

            const table = $('#printOrdersTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                lengthMenu: [
                    [-1, 10, 25, 50, 100, 200],
                    ["الكل", 10, 25, 50, 100, 200]
                ],
                pageLength: -1,
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
                    let api = this.api();
                    let currentTableIds = new Set();

                    // Read IDs from DataTables dataset directly
                    api.rows().every(function() {
                        let rowData = this.data();
                        if (rowData && rowData.id) {
                            currentTableIds.add(String(rowData.id));
                        }
                    });

                    // Also check DOM nodes as fallback
                    $('.order-select-chk').each(function() {
                        let val = $(this).val();
                        if (val) {
                            currentTableIds.add(String(val));
                        }
                    });

                    if (isSelectAllMode && currentTableIds.size > 0) {
                        // If user previously selected all, maintain select-all for the newly filtered rows
                        selectedIds.clear();
                        currentTableIds.forEach(id => selectedIds.add(String(id)));
                    } else {
                        // Prune selectedIds: remove any IDs that are no longer part of the current table results
                        for (let id of Array.from(selectedIds)) {
                            if (!currentTableIds.has(String(id))) {
                                selectedIds.delete(id);
                            }
                        }
                    }

                    // Update row checkboxes state from selectedIds set
                    let allCheckedOnPage = true;
                    let hasRows = false;

                    $('.order-select-chk').each(function() {
                        hasRows = true;
                        let id = String($(this).val());
                        if (selectedIds.has(id)) {
                            $(this).prop('checked', true);
                            $(this).closest('tr').addClass('table-active');
                        } else {
                            $(this).prop('checked', false);
                            $(this).closest('tr').removeClass('table-active');
                            allCheckedOnPage = false;
                        }
                    });

                    $('#checkAll').prop('checked', hasRows && (allCheckedOnPage || isSelectAllMode));
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
                let id = String($(this).val());
                if ($(this).is(':checked')) {
                    selectedIds.add(id);
                    $(this).closest('tr').addClass('table-active');
                } else {
                    selectedIds.delete(id);
                    $(this).closest('tr').removeClass('table-active');
                    isSelectAllMode = false;
                }

                // Check if all on current page are checked
                let allChecked = true;
                let hasRows = false;
                $('.order-select-chk').each(function() {
                    hasRows = true;
                    if (!$(this).is(':checked')) {
                        allChecked = false;
                        return false;
                    }
                });
                $('#checkAll').prop('checked', hasRows && allChecked);
                if (hasRows && allChecked) {
                    isSelectAllMode = true;
                }

                updateUIButtons();
            });

            // Check All change
            $('#checkAll').on('change', function() {
                let isChecked = $(this).is(':checked');
                if (isChecked) {
                    isSelectAllMode = true;
                    $('.order-select-chk').each(function() {
                        let id = String($(this).val());
                        selectedIds.add(id);
                        $(this).prop('checked', true);
                        $(this).closest('tr').addClass('table-active');
                    });
                } else {
                    isSelectAllMode = false;
                    selectedIds.clear();
                    $('.order-select-chk').prop('checked', false).closest('tr').removeClass('table-active');
                }
                updateUIButtons();
            });

            // Clear selection button
            $('#btnClearSelection').on('click', function() {
                isSelectAllMode = false;
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
