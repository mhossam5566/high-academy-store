@extends('dashboard.layouts.layoutMaster')

@section('title', 'الصفوف الدراسية')

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
            tableSelector: '#sliders-table',
            ajaxUrl: '{{ route('dashboard.slider.datatable') }}',
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'operation',
                    name: 'operation',
                    orderable: false,
                    searchable: false
                }
            ],
            delete: {
                selector: '.delete_btn',
                url: '{{ route('dashboard.slider.destroy', ':id') }}',
            },
        });
    </script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">الصفوف الدراسية /</span> إدارة
        </h4>
        <a href="{{ route('dashboard.create.slider') }}" class="btn btn-primary">إضافة صف دراسي</a>
    </div>

    <div class="card">
        <h5 class="card-header">جدول الصفوف الدراسية</h5>
        <div class="card-datatable table-responsive">
            <table class="dt-responsive table" id="sliders-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>عنوان الصف الدراسي</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
