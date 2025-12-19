@extends('dashboard.layouts.layoutMaster')

@section('title', 'طرق الشحن')

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
                <i class="ti ti-truck-delivery me-2"></i>إدارة طرق الشحن
            </h4>
            <a href="{{ route('dashboard.shipping-methods.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>إضافة طريقة شحن جديدة
            </a>
        </div>

        <!-- Shipping Methods Table Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list me-2"></i>قائمة طرق الشحن
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="shippingTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>المحافظة</th>
                                <th>النوع</th>
                                <th>العنوان</th>
                                <th>الأرقام</th>
                                <th>رسوم الخدمة</th>
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
            $('#shippingTable').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, 200, -1],
                    [10, 25, 50, 100, 200, "الكل"]
                ],
                paging: true,
                pageLength: 10,
                stateSave: true,
                stateDuration: -1,
                scrollX: true,
                processing: true,
                serverSide: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
                },
                ajax: '{{ route('dashboard.shipping-methods.datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id',
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'gov_name',
                        name: 'gov_name',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'type_label',
                        name: 'type_label',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let colors = {
                                'مكتب بريد': 'info',
                                'توصيل لباب البيت': 'success',
                                'استلام من المكتبة': 'warning'
                            };
                            let color = colors[data] || 'secondary';
                            return '<span class="badge bg-label-' + color + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'phones_list',
                        name: 'phones_list',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'fee',
                        name: 'fee',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return '<strong>' + data + ' جنيه</strong>';
                        }
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ]
            });
        });

        function deleteShippingMethod(id) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لن تتمكن من التراجع عن هذا!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذفه!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/dashboard/shipping-methods/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(resp => {
                        if (resp.ok) {
                            $('#shippingTable').DataTable().ajax.reload();
                            Swal.fire('تم الحذف!', 'تم حذف طريقة الشحن.', 'success');
                        } else {
                            Swal.fire('خطأ!', 'حدث خطأ أثناء الحذف.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
