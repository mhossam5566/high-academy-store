@extends('dashboard.layouts.layoutMaster')

@section('title', 'تفاصيل طلب الكوبون')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/toastr/toastr.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/toastr/toastr.js') }}"></script>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">طلبات الكوبونات /</span> تفاصيل الطلب
        </h4>
        <a href="{{ route('dashboard.voucher_order') }}" class="btn btn-secondary">العودة للقائمة</a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">الكوبون المطلوب</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if ($coupon->image)
                                <img src="{{ $coupon->image_path }}" alt="صورة الكوبون" class="img-fluid rounded">
                            @else
                                <div class="bg-light p-4 text-center rounded">
                                    <i class="ti ti-photo-x text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">لا توجد صورة</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">اسم الكوبون</th>
                                        <td>{{ $coupon->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>سعر الكوبون</th>
                                        <td>{{ $coupon->price }} جنيه</td>
                                    </tr>
                                    <tr>
                                        <th>عدد الأكواد المتاحة</th>
                                        <td>{{ $coupon->vouchers()->where('is_used', 0)->count() }} كود</td>
                                    </tr>
                                    <tr>
                                        <th>إجمالي الأكواد</th>
                                        <td>{{ $coupon->vouchers()->count() }} كود</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <h5 class="card-header">معلومات العميل</h5>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="30%">اسم العميل</th>
                                <td>{{ $order->user_name ?? ($order->user->name ?? 'غير متوفر') }}</td>
                            </tr>
                            <tr>
                                <th>البريد الإلكتروني</th>
                                <td>{{ $order->user_email ?? ($order->user->email ?? 'غير متوفر') }}</td>
                            </tr>
                            <tr>
                                <th>رقم الهاتف</th>
                                <td>{{ $order->user_phone ?? ($order->user->phone ?? 'غير متوفر') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-4">
                <h5 class="card-header">تفاصيل الطلب</h5>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="30%">رقم الطلب</th>
                                <td>{{ $order->id }}</td>
                            </tr>
                            <tr>
                                <th>الكمية</th>
                                <td>{{ $order->quantity }}</td>
                            </tr>
                            <tr>
                                <th>السعر الإجمالي</th>
                                <td>{{ $coupon->price * $order->quantity }} جنيه</td>
                            </tr>
                            <tr>
                                <th>وسيلة الدفع</th>
                                <td>{{ $order->method }}</td>
                            </tr>
                            <tr>
                                <th>رقم الحساب المحول منه</th>
                                <td>{{ $order->account }}</td>
                            </tr>
                            <tr>
                                <th>صورة التحويل</th>
                                <td>
                                    @if ($order->image)
                                        <img src="{{ asset('images/reciept/') . '/' . $order->image }}" alt="image"
                                            class="img-thumbnail" style="max-width: 300px;">
                                    @else
                                        <span class="text-muted">لا توجد صورة</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>حالة الطلب</th>
                                <td>
                                    @switch($order->state)
                                        @case('pending')
                                            <span class="badge bg-warning">منتظر التحقق</span>
                                        @break

                                        @case('success')
                                            <span class="badge bg-success">طلب ناجح</span>
                                        @break

                                        @case('cancelled')
                                            <span class="badge bg-danger">طلب ملغي</span>
                                        @break

                                        @default
                                            <span class="badge bg-secondary">حالة غير معروفة</span>
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th>وقت الطلب</th>
                                <td>{{ $order->created_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($order->state == 'pending')
                <div class="card">
                    <div class="card-body text-center">
                        <button class="btn btn-success me-2 confirmorder">تأكيد الطلب</button>
                        <button class="btn btn-danger deleteorder">رفض الطلب</button>
                    </div>
                </div>
            @endif

            @if ($order->state == 'success')
                <div class="card">
                    <h5 class="card-header text-success">أكواد الكوبون المرسلة</h5>
                    <div class="card-body">
                        @php
                            $userVouchers = App\Models\Voucher::where('coupon_id', $coupon->id)
                                ->where('user_id', $order->user_id)
                                ->where('is_used', 1)
                                ->where('updated_at', '>=', $order->created_at)
                                ->orderBy('updated_at')
                                ->take($order->quantity)
                                ->get();
                        @endphp

                        @if ($userVouchers->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>الكود</th>
                                            <th>صورة الكود</th>
                                            <th>تاريخ الإرسال</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($userVouchers as $index => $voucher)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <code
                                                        class="bg-primary text-white p-2 rounded">{{ $voucher->code }}</code>
                                                </td>
                                                <td>
                                                    @if ($voucher->image)
                                                        <img src="{{ $voucher->image_path }}" alt="صورة الكود"
                                                            class="img-thumbnail" style="max-width: 100px;">
                                                    @else
                                                        <span class="text-muted">لا توجد صورة</span>
                                                    @endif
                                                </td>
                                                <td>{{ $voucher->updated_at }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="ti ti-alert-triangle me-2"></i>
                                لم يتم العثور على أكواد مرسلة لهذا العميل
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            var csrf = $('meta[name="csrf-token"]').attr('content');
            var itemId = {{ $order->id }};

            $('.deleteorder').on("click", function() {
                Swal.fire({
                    title: "هل انت متأكد",
                    text: "سيتم رفض الطلب",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "متأكد",
                    cancelButtonText: "الغاء",
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
                                    url: "{{ route('dashboard.voucher_order.changestate') }}",
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
                                            text: "خطأ أثناء الرفض",
                                            icon: "error",
                                        });
                                    },
                                });
                            },
                        });
                    }
                });
            });

            $('.confirmorder').on("click", function() {
                Swal.fire({
                    title: "هل انت متأكد",
                    text: "سيتم تأكيد الطلب",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "متأكد",
                    cancelButtonText: "الغاء",
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
                                    url: "{{ route('dashboard.voucher_order.changestate') }}",
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
                                            text: "خطأ أثناء التأكيد",
                                            icon: "error",
                                        });
                                    },
                                });
                            },
                        });
                    }
                });
            });
        });
    </script>
@endsection
