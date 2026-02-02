@extends('dashboard.layouts.layoutMaster')

@section('title', 'أكواد ' . $coupon->name)

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
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
            <span class="text-muted fw-light">الكوبونات /</span> أكواد {{ $coupon->name }}
        </h4>
        <a href="{{ route('dashboard.vouchers.add', $coupon->id) }}" class="btn btn-primary">إضافة كود جديد</a>
    </div>

    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="table table-hover text-nowrap" id="vouchers-table" dir="rtl" style="width: 100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الكود</th>
                        <th>الصورة</th>
                        <th>اسم المستخدم</th>
                        <th>رقم الهاتف</th>
                        <th>الحالة</th>
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
            const table = $('#vouchers-table').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, 200, -1],
                    [10, 25, 50, 100, 200, "الكل"]
                ],
                paging: true,
                pageLength: 10,
                stateSave: true,
                stateDuration: -1,
                scrollX: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                order: [[0, 'desc']],
                ajax: {
                    url: "{{ route('dashboard.vouchers.datatable', $coupon->id) }}"
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'code', name: 'code' },
                    { data: 'image', name: 'image' },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'user_phone', name: 'user_phone' },
                    { data: 'state', name: 'state' },
                    { data: 'operation', name: 'operation', orderable: false }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
                }
            });

            // Delete button handler
            $(document).on('click', '.delete_btn', function(e) {
                e.preventDefault();
                let $btn = $(this);
                let id = $btn.data('id');
                let originalHtml = $btn.html();

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: 'لن تتمكن من التراجع عن هذا!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، احذف!',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $btn.prop('disabled', true);
                        $btn.html('<span class="spinner-border spinner-border-sm"></span>');

                        $.ajax({
                            url: "{{ route('dashboard.vouchers.destroy') }}",
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                id: id
                            },
                            success: function() {
                                Swal.fire('تم الحذف!', 'تم حذف العنصر بنجاح.', 'success');
                                table.ajax.reload();
                            },
                            error: function() {
                                Swal.fire('خطأ!', 'حدث خطأ أثناء الحذف.', 'error');
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
