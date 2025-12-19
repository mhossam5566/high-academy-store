@extends('dashboard.layouts.layoutMaster')

@section('title', 'الأقسام الرئيسية')

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
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">لوحة التحكم /</span> الأقسام الرئيسية
        </h4>
        <a href="{{ route('dashboard.create.main_categories') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i>إضافة قسم جديد
        </a>
    </div>

    <!-- Main Categories Table Card -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="table table-hover" id="categoriesTable">
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
        $(document).ready(function() {
            var table = $('#categoriesTable').DataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],
                ajax: {
                    url: '{{ route('dashboard.main_categories.datatable') }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: '50px'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'icon_image',
                        name: 'icon_image',
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
                order: [
                    [0, 'desc']
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
                }
            });

            // Delete handler
            $(document).on('click', '.delete_btn', function(e) {
                e.preventDefault();
                var category_id = $(this).attr('category_id');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "لا يمكنك التراجع بعد الحذف!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذفها!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('dashboard.main_categories.destroy') }}',
                            type: "POST",
                            data: {
                                '_token': "{{ csrf_token() }}",
                                'id': category_id
                            },
                            success: function(data) {
                                Swal.fire('تم الحذف!', 'تم حذف الفئة بنجاح', 'success');
                                table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                Swal.fire('خطأ!', 'لم يتم الحذف، يرجى المحاولة لاحقًا',
                                    'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
