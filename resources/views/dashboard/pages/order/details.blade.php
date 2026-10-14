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
                            @if (($order->shipping && $order->shipping->type === 'branch') || in_array($order->shipping_method, ['3', '4']))
                                <tr>
                                    <td class="text-muted">فرع المكتبة:</td>
                                    <td>
                                        <span class="badge bg-label-success fs-6">
                                            <i class="ti ti-building-store me-1"></i>{{ $order->shipping ? $order->shipping->name : ($order->shipping_name ?? $order->address) }}
                                        </span>
                                    </td>
                                </tr>
                                @if ($order->shipping?->address || $order->shipping_address)
                                    <tr>
                                        <td class="text-muted">عنوان الفرع:</td>
                                        <td><small class="text-dark">{{ $order->shipping?->address ?? $order->shipping_address }}</small></td>
                                    </tr>
                                @endif
                            @endif
                            @if ($order->near_post)
                                <tr>
                                    <td class="text-muted">أقرب مكتب بريد:</td>
                                    <td>{{ $order->near_post }}</td>
                                </tr>
                            @endif
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
                                        <strong class="text-primary">{{ $order->shipping->name }}</strong>
                                    @elseif($order->shipping_name)
                                        <strong class="text-primary">{{ $order->shipping_name }}</strong>
                                    @elseif($order->shipping_method && is_numeric($order->shipping_method))
                                        @php $m = \App\Models\ShippingMethod::find($order->shipping_method); @endphp
                                        <strong class="text-primary">{{ $m ? $m->name : $order->shipping_method }}</strong>
                                    @elseif($order->shipping_method)
                                        <strong class="text-primary">{{ $order->shipping_method }}</strong>
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

                            @case('returned')
                                <span class="badge bg-dark fs-6">طلب مرتجع</span>
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

    {{-- ===== قسم سجل العمليات ===== --}}
    @if ($order->logs->count() > 0)
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-list-details me-2 text-primary"></i>سجل عمليات الطلب
                    <span class="badge bg-label-primary ms-2">{{ $order->logs->count() }} سطر</span>
                </h5>
                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#orderLogsCollapse" aria-expanded="true">
                    <i class="ti ti-chevron-down"></i>
                </button>
            </div>
            <div class="collapse show" id="orderLogsCollapse">
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0 align-middle" style="font-size: 0.82rem; direction: ltr; text-align: left;">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th style="width:40px">#</th>
                                    <th style="width:80px">Level</th>
                                    <th style="width:80px">Step</th>
                                    <th>Message</th>
                                    <th style="width:160px">Timestamp</th>
                                    <th style="width:120px">IP</th>
                                    <th style="width:60px">Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $levelColors = [
                                        'info'    => 'bg-label-info',
                                        'success' => 'bg-label-success',
                                        'warning' => 'bg-label-warning',
                                        'error'   => 'bg-label-danger',
                                    ];
                                    $stepColors = [
                                        'START'    => 'bg-dark text-white',
                                        'STEP 1'   => 'bg-primary text-white',
                                        'STEP 2'   => 'bg-primary text-white',
                                        'STEP 3'   => 'bg-primary text-white',
                                        'STEP 4'   => 'bg-primary text-white',
                                        'STEP 5'   => 'bg-primary text-white',
                                        'WHATSAPP' => 'bg-success text-white',
                                        'END'      => 'bg-dark text-white',
                                    ];
                                @endphp
                                @foreach ($order->logs as $i => $log)
                                    @php
                                        $rowClass = match ($log->level) {
                                            'error'   => 'table-danger',
                                            'warning' => 'table-warning',
                                            'success' => 'table-success',
                                            default   => '',
                                        };
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td class="text-muted">{{ $i + 1 }}</td>
                                        <td>
                                            <span class="badge {{ $levelColors[$log->level] ?? 'bg-label-secondary' }}">
                                                {{ strtoupper($log->level) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($log->step)
                                                <span class="badge {{ $stepColors[$log->step] ?? 'bg-secondary' }}" style="font-size:0.7rem">
                                                    {{ $log->step }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="{{ in_array($log->level, ['error']) ? 'fw-bold text-danger' : '' }}">
                                                {{ $log->message }}
                                            </span>
                                        </td>
                                        <td class="text-muted" style="font-size:0.75rem; white-space:nowrap">
                                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                                        </td>
                                        <td class="text-muted" style="font-size:0.75rem">
                                            {{ $log->ip ?? '—' }}
                                        </td>
                                        <td class="text-center">
                                            @if ($log->context)
                                                <button type="button" class="btn btn-xs btn-icon btn-outline-secondary p-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#logContextModal{{ $log->id }}"
                                                    title="عرض البيانات">
                                                    <i class="ti ti-code" style="font-size:0.8rem"></i>
                                                </button>

                                                {{-- Modal for context --}}
                                                <div class="modal fade" id="logContextModal{{ $log->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">
                                                                    <i class="ti ti-code me-2"></i>
                                                                    {{ $log->step ?? 'Log' }} #{{ $log->id }} — Context Data
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body p-0">
                                                                <pre class="mb-0 p-3 bg-dark text-light rounded-bottom" style="max-height:400px; overflow-y:auto; font-size:0.8rem; direction:ltr; text-align:left">{{ json_encode($log->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Process ID footer --}}
                    @php $firstLog = $order->logs->first(); @endphp
                    @if ($firstLog && $firstLog->process_id)
                        <div class="px-3 py-2 border-top bg-light" style="font-size:0.75rem; direction:ltr; text-align:left;">
                            <i class="ti ti-fingerprint me-1 text-muted"></i>
                            <strong>Process ID:</strong>
                            <code>{{ $firstLog->process_id }}</code>
                        </div>
                    @endif
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
