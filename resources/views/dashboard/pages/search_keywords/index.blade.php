@extends('dashboard.layouts.layoutMaster')

@section('title', 'كلمات البحث')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
@endsection

@section('content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">كلمات البحث</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item active">كلمات البحث</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard.search-keywords.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>إضافة كلمة بحث جديدة
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-search me-2"></i>قائمة كلمات البحث
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="keywordsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>كلمة البحث</th>
                            <th>الترتيب</th>
                            <th>الحالة</th>
                            <th>العمليات</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            $('#keywordsTable').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],
                "paging": true,
                "pageLength": 10,
                "stateSave": true,
                "stateDuration": -1,
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "sort": false,
                "responsive": true,
                "ajax": {
                    "url": "{{ route('dashboard.search-keywords.datatable') }}",
                    "type": "GET"
                },
                "columns": [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'keyword',
                        name: 'keyword'
                    },
                    {
                        data: 'display_order',
                        name: 'display_order'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
                }
            });
        });

        function deleteKeyword(id) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'لن تتمكن من استرجاع هذه الكلمة بعد الحذف!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('dashboard.search-keywords.destroy', ':id') }}'.replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('تم الحذف!', response.message, 'success');
                                $('#keywordsTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire('خطأ!', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('خطأ!', 'حدث خطأ أثناء حذف كلمة البحث', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
