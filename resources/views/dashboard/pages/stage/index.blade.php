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

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">المراحل التعليمية /</span> إدارة
        </h4>
        <a href="{{ route('dashboard.create.education_stages') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i>إضافة مرحلة تعليمية
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">جدول المراحل التعليمية</h5>
        <div class="card-datatable table-responsive">
            <table class="dt-responsive table table-hover" id="stages-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">#</th>
                        <th>اسم المرحلة</th>
                        <th style="width: 180px;">العمليات</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            const table = $('#stages-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.stage.datatable') }}',
                columns: [
                    {
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
                const $btn = $(this);
                const id = $btn.data('id') || $btn.attr('stage_id');
                const originalHtml = $btn.html();

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: 'لن تتمكن من التراجع بعد حذف هذه المرحلة التعليمية!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء',
                    customClass: {
                        confirmButton: 'btn btn-danger me-3',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false,
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $btn.prop('disabled', true);
                        $btn.html('<span class="spinner-border spinner-border-sm me-1"></span>جاري الحذف...');

                        $.ajax({
                            url: '{{ route('dashboard.stage.destroy') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'تم الحذف!',
                                    text: 'تم حذف المرحلة التعليمية بنجاح.',
                                    icon: 'success',
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                });
                                table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                let errorMsg = 'حدث خطأ أثناء محاولة الحذف، يرجى المحاولة لاحقاً.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    title: 'خطأ!',
                                    text: errorMsg,
                                    icon: 'error',
                                    customClass: {
                                        confirmButton: 'btn btn-primary'
                                    }
                                });
                            },
                            complete: function() {
                                $btn.prop('disabled', false);
                                $btn.html(originalHtml);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
