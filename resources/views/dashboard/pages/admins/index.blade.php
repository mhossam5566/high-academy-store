@extends('dashboard.layouts.layoutMaster')

@section('title', 'إدارة المديرين')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
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
                <i class="ti ti-users me-2"></i>إدارة حسابات المديرين
            </h4>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard.admins.create') }}" class="btn btn-primary">
                    <i class="ti ti-user-plus me-1"></i>إضافة مدير جديد
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Admins Table Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>قائمة المديرين والمشرفين
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="adminsTable">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>المدير</th>
                                <th>الأدوار والرتب</th>
                                <th class="text-center">الإجراءات</th>
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
            var table = $('#adminsTable').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],
                "pageLength": 10,
                'scrollX': true,
                "processing": true,
                "serverSide": true,
                "sort": false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
                },
                "ajax": {
                    "url": "{{ route('dashboard.admins.datatable') }}",
                    "type": "GET"
                },
                "columns": [
                    { data: 'id', name: 'id', className: 'text-center' },
                    { data: 'admin_info', name: 'name' },
                    { data: 'roles', name: 'roles' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
                ]
            });
        });

        function deleteAdmin(id) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم حذف هذا الحساب الإداري نهائياً!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('dashboard.admins.destroy', ':id') }}'.replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('تم الحذف!', response.message, 'success');
                                $('#adminsTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire('تنبيه!', response.message, 'warning');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('خطأ!', 'حدث خطأ أثناء حذف الحساب', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
