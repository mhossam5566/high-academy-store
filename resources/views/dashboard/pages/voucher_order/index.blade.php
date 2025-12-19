@extends('dashboard.layouts.layoutMaster')

@section('title', 'طلبات الكوبونات')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet"
        href="{{ asset('dashboard/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/toastr/toastr.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/toastr/toastr.js') }}"></script>
@endsection

@section('page-script')
    <script>
        CRUDHelper.init({
            tableSelector: '#voucher-orders-table',
            ajaxUrl: '{{ route('dashboard.voucher_order.datatable') }}',
            ajaxData: function(d) {
                d.state = "{{ request()->query('state') ?? '' }}";
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'customer_name',
                    name: 'customer_name'
                },
                {
                    data: 'customer_email',
                    name: 'customer_email'
                },
                {
                    data: 'customer_phone',
                    name: 'customer_phone'
                },
                {
                    data: 'coupon',
                    name: 'coupon'
                },
                {
                    data: 'quantity',
                    name: 'quantity'
                },
                {
                    data: 'method',
                    name: 'method'
                },
                {
                    data: 'account',
                    name: 'account'
                },
                {
                    data: 'image',
                    name: 'image',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'state',
                    name: 'state',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'details',
                    name: 'details',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    </script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">طلبات الكوبونات /</span> إدارة
        </h4>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="btn-group" role="group">
                <a href="{{ route('dashboard.voucher_order') }}"
                    class="btn {{ !request()->has('state') ? 'btn-primary' : 'btn-outline-primary' }}">
                    كل الحالات
                </a>
                <a href="{{ route('dashboard.voucher_order') }}?state=pending"
                    class="btn {{ request('state') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                    منتظر التحقق
                </a>
                <a href="{{ route('dashboard.voucher_order') }}?state=success"
                    class="btn {{ request('state') == 'success' ? 'btn-success' : 'btn-outline-success' }}">
                    طلب ناجح
                </a>
                <a href="{{ route('dashboard.voucher_order') }}?state=cancelled"
                    class="btn {{ request('state') == 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">
                    تم الإلغاء
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <h5 class="card-header">جدول طلبات الكوبونات</h5>
        <div class="card-datatable table-responsive">
            <table class="dt-responsive table" id="voucher-orders-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم العميل</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>الكوبون</th>
                        <th>الكمية</th>
                        <th>وسيلة الدفع</th>
                        <th>رقم الحساب</th>
                        <th>إيصال الدفع</th>
                        <th>حالة العملية</th>
                        <th>التفاصيل</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
