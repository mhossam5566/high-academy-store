@extends('dashboard.layouts.layoutMaster')

@section('title', 'المواد الدراسية')

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
            <span class="text-muted fw-light">لوحة التحكم /</span> المواد الدراسية
        </h4>
        <a href="{{ route('dashboard.create.category') }}" class="btn btn-primary">إضافة مادة دراسية</a>
    </div>

    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="table" id="categories-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الصورة</th>
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
            tableSelector: '#categories-table',
            ajaxUrl: '{{ route('dashboard.category.datatable') }}',
            columns: [{
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
            delete: {
                selector: '.delete_btn',
                url: '{{ route('dashboard.category.destroy') }}'
            }
        });
    </script>
@endsection
