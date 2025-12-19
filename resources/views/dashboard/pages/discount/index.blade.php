@extends('dashboard.layouts.layoutMaster')

@section('title', 'قسائم الخصم')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet"
        href="{{ asset('dashboard/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="ti ti-ticket me-2"></i>إدارة قسائم الخصم
            </h4>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard.discount.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>إضافة قسيمة جديدة
                </a>
                <button id="toggleDiscountFeature" class="btn btn-warning">
                    @if ($discountSetting && $discountSetting->discount_enabled)
                        <i class="ti ti-lock me-1"></i>تعطيل الخصم
                    @else
                        <i class="ti ti-lock-open me-1"></i>تفعيل الخصم
                    @endif
                </button>
            </div>
        </div>

        <!-- Discount Status Alert -->
        <div class="alert alert-{{ $discountSetting && $discountSetting->discount_enabled ? 'success' : 'warning' }} d-flex align-items-center mb-4"
            role="alert">
            <i class="ti ti-info-circle me-2 fs-4"></i>
            <div>
                <strong>حالة نظام الخصم:</strong>
                @if ($discountSetting && $discountSetting->discount_enabled)
                    مفعّل - يمكن للعملاء استخدام القسائم
                @else
                    معطّل - لا يمكن استخدام القسائم حالياً
                @endif
            </div>
        </div>

        <!-- Coupons Table Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>قائمة القسائم
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="couponsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>رمز القسيمة</th>
                                <th>قيمة الخصم (جنيه)</th>
                                <th>حد الاستخدام</th>
                                <th>عدد الاستخدامات</th>
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
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            const table = $('#couponsTable').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
                },
                ajax: "{{ route('dashboard.discount.datatable') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        className: 'text-center'
                    },
                    {
                        data: 'code',
                        name: 'code',
                        render: function(data, type, row) {
                            return '<span class="badge bg-label-primary">' + data + '</span>';
                        }
                    },
                    {
                        data: 'discount',
                        name: 'discount',
                        className: 'text-center',
                        render: function(data, type, row) {
                            return '<strong>' + data + ' جنيه</strong>';
                        }
                    },
                    {
                        data: 'usage_limit',
                        name: 'usage_limit',
                        className: 'text-center',
                        render: function(data, type, row) {
                            return data ? data : '<span class="text-muted">غير محدود</span>';
                        }
                    },
                    {
                        data: 'used',
                        name: 'used',
                        className: 'text-center',
                        render: function(data, type, row) {
                            let color = data > 0 ? 'info' : 'secondary';
                            return '<span class="badge bg-label-' + color + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (row.usage_limit && row.used >= row.usage_limit) {
                                return '<span class="badge bg-danger">مستنفذة</span>';
                            }
                            return '<span class="badge bg-success">نشطة</span>';
                        }
                    },
                    {
                        data: 'operation',
                        name: 'operation',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ]
            });

            // Toggle Discount Feature Button
            $('#toggleDiscountFeature').click(function() {
                const button = $(this);
                button.prop('disabled', true);

                $.ajax({
                    url: "{{ route('dashboard.discount.toggle') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        button.prop('disabled', false);
                        Swal.fire('خطأ!', xhr.responseJSON?.error || 'حدث خطأ ما!', 'error');
                    }
                });
            });

            // Delete Coupon
            $('#couponsTable').on('click', '.delete_btn', function() {
                const couponId = $(this).data('id');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "لن تتمكن من التراجع عن هذا الإجراء!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذفها!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('dashboard.discount.destroy') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: couponId
                            },
                            success: function(response) {
                                Swal.fire('تم الحذف!', response.message, 'success');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire('خطأ!', xhr.responseJSON?.error ||
                                    'حدث خطأ ما!', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
