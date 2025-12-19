@extends('dashboard.layouts.layoutMaster')

@section('title', 'أكواد ' . $coupon->name)

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

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">الكوبونات /</span> أكواد {{ $coupon->name }}
        </h4>
        <a href="{{ route('dashboard.vouchers.add', $coupon->id) }}" class="btn btn-primary">إضافة كود جديد</a>
    </div>

    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="table" id="vouchers-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الكود</th>
                        <th>الصورة</th>
                        <th>اسم المستخدم</th>
                        <th>رقم الهاتف</th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        CRUDHelper.init({
            tableSelector: '#vouchers-table',
            ajaxUrl: '{{ route('dashboard.vouchers.datatable', $coupon->id) }}',
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'image',
                    name: 'image'
                },
                {
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'user_phone',
                    name: 'user_phone'
                },
                {
                    data: 'state',
                    name: 'state'
                },
                {
                    data: 'operation',
                    name: 'operation',
                    orderable: false
                }
            ],
            delete: {
                selector: '.delete_btn',
                url: '{{ route('dashboard.vouchers.destroy') }}'
            }
        });
    </script>
@endsection
