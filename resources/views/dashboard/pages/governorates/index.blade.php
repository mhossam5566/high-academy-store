@extends('dashboard.layouts.layoutMaster')

@section('title', 'إدارة المحافظات')

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
                <i class="ti ti-map-pin me-2"></i>إدارة المحافظات
            </h4>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard.governorates.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>إضافة محافظة
                </a>
            </div>
        </div>

        <!-- Governorates Table Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>قائمة المحافظات
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="governoratesTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم بالعربية</th>
                                <th>الاسم بالإنجليزية</th>
                                <th>أسعار الشحن</th>
                                <th>عدد المدن</th>
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
            var columns = [{
                    data: 'id',
                    name: 'id',
                    className: 'text-center'
                },
                {
                    data: 'name_ar',
                    name: 'name_ar'
                },
                {
                    data: 'name_en',
                    name: 'name_en'
                },
                {
                    data: 'shipping_price',
                    name: 'shipping_price',
                    className: 'text-center'
                },
                {
                    data: 'cities_count',
                    name: 'cities_count',
                    className: 'text-center'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ];

            var table = $('#governoratesTable').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],
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
                    "url": "{{ route('dashboard.governorates.datatable') }}",
                    "type": "GET"
                },
                "columns": columns
            });
        });

        function deleteGovernorate(id) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'لن تتمكن من استرجاع هذه المحافظة بعد الحذف!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('dashboard.governorates.destroy', ':id') }}'.replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('تم الحذف!', response.message, 'success');
                                $('#governoratesTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire('خطأ!', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('خطأ!', 'حدث خطأ أثناء حذف المحافظة', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
