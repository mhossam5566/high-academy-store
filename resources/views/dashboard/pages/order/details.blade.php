@extends('dashboard.layouts.layoutMaster')

@section('title', 'تفاصيل الطلب #' . $order->id)

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">الطلبات /</span> تفاصيل الطلب #{{ $order->id }}
        </h4>
        <a href="{{ route('dashboard.orders') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
        </a>
    </div>

    <!-- Order Items Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-shopping-cart me-2"></i>المنتجات المطلوبة
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>المنتج</th>
                            <th>السعر</th>
                            <th>الكمية</th>
                            <th>اللون</th>
                            <th>الحجم</th>
                            <th>المبلغ الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderDetails as $item)
                            <tr>
                                <td><strong>{{ $item->products->short_name }}</strong></td>
                                <td>{{ $item->price }} جنيه</td>
                                <td>{{ $item->amout }}</td>
                                <td>{{ $item->color ?? '-' }}</td>
                                <td>{{ $item->size ?? '-' }}</td>
                                <td><strong>{{ $item->total_price }} جنيه</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="5" class="text-end">الإجمـــالي:</th>
                            <th>{{ $order->amount }} جنيه</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Order Details Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-info-circle me-2"></i>تفاصيل الطلب
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h6 class="text-muted mb-3">معلومات العميل</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="40%">رقم الطلب:</td>
                                <td><strong>#{{ $order->id }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">الاسم:</td>
                                <td><strong>{{ $order->name }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">رقم الموبايل:</td>
                                <td><strong>{{ $order->mobile }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">العنوان:</td>
                                <td>{{ $order->address }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">العنوان التفصيلي:</td>
                                <td>{{ $order->address2 }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">أقرب مكتب بريد:</td>
                                <td>{{ $order->near_post }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h6 class="text-muted mb-3">معلومات الشحن والدفع</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="40%">نوع الشحن:</td>
                                <td>
                                    @if ($order->shipping)
                                        <strong>{{ $order->shipping->name }}</strong>
                                    @elseif($order->shipping_method)
                                        <strong>{{ $order->shipping_method }}</strong>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">قيمة المنتجات:</td>
                                <td><strong>{{ $order->amount }} جنيه</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">رسوم الشحن:</td>
                                <td><strong>{{ $order->delivery_fee }} جنيه</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">إجمالي المدفوع:</td>
                                <td class="text-success"><strong>{{ $order->total }} جنيه</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">وسيلة الدفع:</td>
                                <td><strong>{{ $order->method }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">رقم الحساب:</td>
                                <td>{{ $order->account }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">حالة الطلب</h6>
                        @switch($order->status)
                            @case('new')
                                <span class="badge bg-warning fs-6">طلب جديد</span>
                            @break

                            @case('success')
                                <span class="badge bg-success fs-6">طلب ناجح</span>
                            @break

                            @case('cancelled')
                                <span class="badge bg-danger fs-6">طلب ملغي</span>
                            @break

                            @case('pending')
                                <span class="badge bg-info fs-6">طلب معلق</span>
                            @break

                            @case('reserved')
                                <span class="badge bg-primary fs-6">طلب محجوز</span>
                            @break

                            @default
                                <span class="badge bg-secondary fs-6">حالة غير معروفة</span>
                        @endswitch
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded p-3">
                        <h6 class="text-muted mb-2">وقت الطلب</h6>
                        <p class="mb-0"><i class="ti ti-clock me-1"></i>{{ $order->created_at }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Card -->
    @if ($order->status == 'new')
        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-success confirmorder" id="accept">
                        <i class="ti ti-check me-1"></i>تأكيد الطلب
                    </button>
                    <button class="btn btn-danger deleteorder" id="cancle">
                        <i class="ti ti-x me-1"></i>رفض الطلب
                    </button>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('page-script')
    <script>
        /***** DELETE order ******/
        $('.deleteorder').on("click", function() {
            var itemId = {{ $order->id }};
            var csrf = $('meta[name="csrf-token"]').attr('content');

            Swal.fire({
                title: "هل أنت متأكد؟",
                text: "سيتم رفض الطلب",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "متأكد",
                cancelButtonText: "إلغاء",
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "جاري الرفض",
                        text: "يتم الآن رفض الطلب",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                            $.ajax({
                                url: "{{ route('dashboard.changestate') }}",
                                type: "POST",
                                contentType: "application/json",
                                data: JSON.stringify({
                                    _token: csrf,
                                    id: itemId,
                                    state: 2
                                }),
                                success: function(data) {
                                    Swal.fire({
                                        title: "تم الرفض",
                                        text: "تم رفض الطلب بنجاح",
                                        icon: "success",
                                    }).then(() => {
                                        location.reload(true);
                                    });
                                },
                                error: function(error) {
                                    console.error("Error:", error);
                                    Swal.fire({
                                        title: "خطأ",
                                        text: "خطأ أثناء رفض الطلب",
                                        icon: "error",
                                    });
                                },
                            });
                        },
                    });
                }
            });
        });

        /***** Accept order ******/
        $('.confirmorder').on("click", function() {
            var itemId = {{ $order->id }};
            var csrf = $('meta[name="csrf-token"]').attr('content');

            Swal.fire({
                title: "هل أنت متأكد؟",
                text: "سيتم تأكيد الطلب",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "متأكد",
                cancelButtonText: "إلغاء",
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "جاري التأكيد",
                        text: "يتم الآن تأكيد الطلب",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                            $.ajax({
                                url: "{{ route('dashboard.changestate') }}",
                                type: "POST",
                                contentType: "application/json",
                                data: JSON.stringify({
                                    _token: csrf,
                                    id: itemId,
                                    state: 1
                                }),
                                success: function(data) {
                                    Swal.fire({
                                        title: "تم التأكيد",
                                        text: "تم تأكيد الطلب بنجاح",
                                        icon: "success",
                                    }).then(() => {
                                        location.reload(true);
                                    });
                                },
                                error: function(error) {
                                    console.error("Error:", error);
                                    Swal.fire({
                                        title: "خطأ",
                                        text: "خطأ أثناء تأكيد الطلب",
                                        icon: "error",
                                    });
                                },
                            });
                        },
                    });
                }
            });
        });
    </script>
@endsection
