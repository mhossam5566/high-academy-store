@extends('dashboard.layouts.layoutMaster')

@section('title', 'العروض')

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
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="ti ti-discount-2 me-2"></i>إدارة العروض
            </h4>
            <a href="{{ route('dashboard.create.offers') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>إضافة عرض جديد
            </a>
        </div>

        <!-- Offers Table Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>قائمة العروض
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="offersTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الصورة</th>
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
        CRUDHelper.init({
            tableSelector: '#offersTable',
            ajaxUrl: '{{ route('dashboard.offers.datatable') }}',
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'image',
                    name: 'image',
                    orderable: false,
                    searchable: false
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
                url: '{{ route('dashboard.offers.destroy', ':id') }}',
            },
        });
    </script>
@endsection
