@extends('dashboard.layouts.layoutMaster')

@section('title', 'المراحل التعليمية')

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
            tableSelector: '#stages-table',
            ajaxUrl: '{{ route('dashboard.stage.datatable') }}',
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
                url: '{{ route('dashboard.stage.destroy', ':id') }}',
            },
        });
    </script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">المراحل التعليمية /</span> إدارة
        </h4>
        <a href="{{ route('dashboard.create.education_stages') }}" class="btn btn-primary">إضافة مرحلة تعليمية</a>
    </div>

    <div class="card">
        <h5 class="card-header">جدول المراحل التعليمية</h5>
        <div class="card-datatable table-responsive">
            <table class="dt-responsive table" id="stages-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المرحلة</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
