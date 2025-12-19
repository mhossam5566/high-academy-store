@extends('dashboard.layouts.layoutMaster')

@section('title', 'طلبات الباركود')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet"
        href="{{ asset('dashboard/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('dashboard.orders.barcode') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-right me-1"></i>العودة لاختيار طريقة الشحن
            </a>
        </div>

        <!-- Shipping Info Card -->
        @if (request('shipping') && request('shipping') !== 'all')
            @php
                $shippingType = request('shipping');
                $typeLabels = [
                    'branch' => 'استلام من الفرع',
                    'post' => 'البريد المصري',
                    'home' => 'التوصيل للمنزل',
                ];
                $typeIcons = [
                    'branch' => 'ti ti-building-store',
                    'post' => 'ti ti-mail',
                    'home' => 'ti ti-home',
                ];
                $typeColors = [
                    'branch' => 'success',
                    'post' => 'info',
                    'home' => 'warning',
                ];
            @endphp

            <div class="card bg-{{ $typeColors[$shippingType] ?? 'primary' }} text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-white mb-2">
                                <i class="{{ $typeIcons[$shippingType] ?? 'ti ti-truck' }} me-2"></i>
                                {{ $typeLabels[$shippingType] ?? $shippingType }}
                            </h5>
                            <p class="mb-0 opacity-75">عرض طلبات {{ $typeLabels[$shippingType] ?? $shippingType }}</p>
                        </div>
                        <div>
                            <i class="ti ti-barcode" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-white mb-2">
                                <i class="ti ti-list me-2"></i>جميع الطلبات
                            </h5>
                            <p class="mb-0 opacity-75">عرض طلبات جميع طرق الشحن</p>
                        </div>
                        <div>
                            <i class="ti ti-barcode" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Orders Table Card -->
        <div class="card">
            @if (request('shipping') != 'branch')
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-list me-2"></i>الطلبات والباركود
                    </h5>
                </div>
            @endif

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="myTable">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>الاسم</th>
                                <th>الباركود</th>
                                <th>أضافه الباركود</th>
                                @if (request('shipping') === 'branch')
                                    <th>حالة التتبع</th>
                                    <th>الإجراءات</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],
                "paging": true,
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
                    "url": "{{ route('dashboard.orders.datatable') }}",
                    "type": "GET",
                    "data": function(d) {
                        d.state = "success";
                        @if (request('shipping') && request('shipping') !== 'all')
                            d.shipping = "{{ request('shipping') }}";
                        @endif
                    }
                },
                "columns": [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'barcode',
                        name: 'barcode'
                    },
                    {
                        data: 'admin_addbarcode',
                        name: 'admin_addbarcode',
                    },
                    @if (request('shipping') === 'branch')
                        {
                            data: 'tracker_state',
                            name: 'tracker_state',
                        }, {
                            data: 'branch_actions',
                            name: 'branch_actions',
                        },
                    @endif
                ],
            });
        });

        // Individual notification button click
        $(document).on('click', '.send-notification', function() {
            const orderId = $(this).data('order-id');
            const button = $(this);

            // Prompt for custom message
            const customMessage = prompt('اكتب رسالة مخصصة للعميل:', 'طلبك جاهز للاستلام من الفرع');

            if (!customMessage || customMessage.trim() === '') {
                alert('الرسالة المخصصة مطلوبة');
                return;
            }

            // Disable button and show loading
            button.prop('disabled', true).html('<i class="ti ti-loader ti-spin"></i> جاري الإرسال...');

            $.post('{{ route('dashboard.orders.sendIndividualNotification') }}', {
                    order_id: orderId,
                    custom_message: customMessage.trim(),
                    _token: '{{ csrf_token() }}'
                })
                .done(function(response) {
                    alert(response.message);

                    // Reload DataTable to show updated status
                    $('#myTable').DataTable().ajax.reload();
                })
                .fail(function(xhr) {
                    const response = xhr.responseJSON;
                    alert(response?.message || 'حدث خطأ أثناء إرسال الإشعار');
                })
                .always(function() {
                    // Re-enable button (will be updated by DataTable reload)
                    button.prop('disabled', false);
                });
        });

        // Handle status change dropdown
        function handleStatusChange(selectElement) {
            let selectedValue = selectElement.value;
            if (selectedValue.startsWith("http")) {
                window.location.href = selectedValue;
            } else {
                let orderId = selectElement.getAttribute('data-order-id');

                $.post('{{ route('dashboard.changestate') }}', {
                        id: orderId,
                        state: selectedValue,
                        _token: '{{ csrf_token() }}'
                    })
                    .done(function(response) {
                        if (response.success) {
                            alert(response.msg);
                            $('#myTable').DataTable().ajax.reload();
                        } else {
                            alert('حدث خطأ: ' + response.msg);
                        }
                    })
                    .fail(function(xhr) {
                        alert('حدث خطأ أثناء تحديث حالة الطلب');
                    })
                    .always(function() {
                        // Reset dropdown
                        selectElement.selectedIndex = 0;
                    });
            }
        }
    </script>
@endsection
