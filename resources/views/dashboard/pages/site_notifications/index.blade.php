@extends('dashboard.layouts.layoutMaster')

@section('title', 'تنبيهات الموقع')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">تنبيهات الموقع</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item active">تنبيهات الموقع</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('dashboard.site_notifications.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i>إضافة تنبيه جديد
        </a>
    </div>

    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-notifications table border-top">
                <thead>
                    <tr>
                        <th>المحتوى</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('page-script')
    <script>
        $(function() {
            var dt_notifications_table = $('.datatables-notifications');

            if (dt_notifications_table.length) {
                var dt_notifications = dt_notifications_table.DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('dashboard.site_notifications.datatable') }}",
                    columns: [
                        { data: 'content' },
                        { data: 'is_active' },
                        { data: 'actions', orderable: false, searchable: false }
                    ],
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
                    }
                });
            }
        });

        function deleteNotification(id) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لن تتمكن من التراجع عن هذا!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، احذفه!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('dashboard/site-notifications/destroy') }}/" + id,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('تم الحذف!', response.message, 'success');
                                $('.datatables-notifications').DataTable().ajax.reload();
                            }
                        }
                    });
                }
            });
        }
    </script>
@endsection
