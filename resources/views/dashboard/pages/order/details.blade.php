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

    {{-- ===== قسم سجل العمليات (عرض مُبسَّط للمسؤول) ===== --}}
    @if ($order->logs->count() > 0)
        @php
            // ===== ترجمة الرسائل التقنية إلى وصف مفهوم للمسؤول =====
            $messageMap = [
                // بداية ونهاية العملية
                '=== FAWRY WEBHOOK PROCESS START ==='             => 'استُلم إشعار دفع جديد من فوري',
                '=== FAWRY WEBHOOK PROCESS COMPLETED SUCCESSFULLY ===' => 'اكتملت عملية تأكيد الدفع بنجاح',
                '=== PROCESS TERMINATED - NO ORDER STATUS ==='    => 'توقفت العملية: لم يرد حالة الطلب في الإشعار',
                '=== PROCESS TERMINATED - INVALID SIGNATURE ===' => 'توقفت العملية: فشل التحقق من صحة التوقيع الأمني',
                '=== PROCESS TERMINATED - ORDER NOT FOUND ==='   => 'توقفت العملية: لم يُعثر على الطلب في النظام',

                // الخطوة 1
                'STEP 1: Checking if orderStatus exists'          => 'التحقق من وجود حالة الطلب في الإشعار',
                'ERROR: orderStatus not found in request'         => 'خطأ: حالة الطلب غائبة عن بيانات الإشعار',

                // الخطوة 2
                'STEP 2: Extracting required data'                => 'استخراج بيانات الدفع من الإشعار',
                'Extracted payment data'                          => 'تم استخراج بيانات الدفع',

                // الخطوة 3
                'STEP 3: Verifying signature'                     => 'التحقق من التوقيع الأمني للإشعار',
                'Signature check'                                 => 'مقارنة التوقيع الأمني',
                'ERROR: Signature verification failed!'           => 'خطأ: التوقيع الأمني لا يطابق المتوقع',
                'SUCCESS: Signature verified'                     => 'تم التحقق من صحة التوقيع الأمني ✓',

                // الخطوة 4 – البحث عن الطلب
                'ERROR: Order not found!'                         => 'خطأ: لم يُعثر على الطلب في قاعدة البيانات',

                // الخطوة 5 – معالجة الدفع
                'Processing PAID status'                          => 'جارٍ معالجة حالة الدفع: مدفوع',
                'Processing UNPAID status'                        => 'جارٍ معالجة حالة الدفع: غير مدفوع',
                'Set is_paid to 1'                                => 'تم تسجيل الطلب كمدفوع',
                'Checking order details for reserved products'    => 'التحقق من بنود الطلب لرصد المنتجات المحجوزة',
                'Starting product quantity restoration'           => 'جارٍ استعادة كميات المنتجات بعد الإلغاء',
                'Order saved successfully'                        => 'تم حفظ الطلب بنجاح',
                'Set tracker to: shipped'                         => 'تم تحديث مرحلة الشحن إلى: قيد الشحن',
                'Kept state as reserved (2)'                      => 'المنتج لا يزال محجوزاً',
                'Changed state from unavailable (0) to available (1)' => 'تم تغيير حالة المنتج من غير متاح إلى متاح',

                // البريد الإلكتروني
                'Email sent successfully'                         => 'تم إرسال بريد التأكيد للعميل ✓',

                // واتساب
            ];

            // دالة مساعدة للترجمة مع احتساب القيم الديناميكية
            $translateMessage = function(string $raw) use ($messageMap): string {
                // بحث مباشر
                if (isset($messageMap[$raw])) {
                    return $messageMap[$raw];
                }
                // أنماط ديناميكية
                if (str_starts_with($raw, 'SUCCESS: orderStatus found - ')) {
                    $val = str_replace('SUCCESS: orderStatus found - ', '', $raw);
                    $statusAr = match($val) {
                        'PAID'   => 'مدفوع',
                        'UNPAID' => 'غير مدفوع',
                        default  => $val,
                    };
                    return "حالة الدفع المستلمة: {$statusAr} ✓";
                }
                if (str_starts_with($raw, 'STEP 5: Processing order status - ')) {
                    $val = str_replace('STEP 5: Processing order status - ', '', $raw);
                    $statusAr = match($val) {
                        'PAID'   => 'مدفوع',
                        'UNPAID' => 'غير مدفوع',
                        default  => $val,
                    };
                    return "معالجة حالة الطلب: {$statusAr}";
                }
                if (str_starts_with($raw, 'STEP 4: Finding order')) {
                    return 'البحث عن الطلب في قاعدة البيانات';
                }
                if (str_starts_with($raw, 'SUCCESS: Order found - ID: ')) {
                    $id = str_replace('SUCCESS: Order found - ID: ', '', $raw);
                    return "تم العثور على الطلب رقم #{$id} ✓";
                }
                if (str_starts_with($raw, 'Order status changed to: ')) {
                    preg_match('/Order status changed to: (\S+)/', $raw, $m);
                    $statusAr = match($m[1] ?? '') {
                        'success'   => 'ناجح',
                        'cancelled' => 'ملغي',
                        'reserved'  => 'محجوز',
                        'pending'   => 'معلق',
                        default     => $m[1] ?? $raw,
                    };
                    $isAuto = str_contains($raw, 'automatically');
                    return "تم تغيير حالة الطلب إلى: {$statusAr}" . ($isAuto ? ' (تلقائياً بعد تأكيد بوابة الدفع)' : '');
                }
                if (str_starts_with($raw, 'Product found - ID: ')) {
                    preg_match('/ID: (\d+), State: (\d+), Qty: (\d+)/', $raw, $m);
                    $stateAr = match((int)($m[2] ?? -1)) {
                        0 => 'غير متاح', 1 => 'متاح', 2 => 'محجوز', default => $m[2] ?? '?'
                    };
                    return "منتج رقم #{$m[1]}: الحالة {$stateAr}، الكمية {$m[3]}";
                }
                if (str_starts_with($raw, 'Reserved product detected - ID: ')) {
                    $id = str_replace('Reserved product detected - ID: ', '', $raw);
                    return "⚠️ المنتج رقم #{$id} محجوز مسبقاً";
                }
                if (str_starts_with($raw, 'No product found for detail ID: ')) {
                    $id = str_replace('No product found for detail ID: ', '', $raw);
                    return "تحذير: لم يُعثر على منتج للعنصر رقم #{$id}";
                }
                if (str_starts_with($raw, 'Processing detail ID: ')) {
                    preg_match('/product_id: (\d+), amount: (\d+)/', $raw, $m);
                    return "معالجة المنتج رقم #{$m[1]}، الكمية: {$m[2]}";
                }
                if (str_starts_with($raw, 'Product not found for ID: ')) {
                    $id = str_replace('Product not found for ID: ', '', $raw);
                    return "تحذير: المنتج رقم #{$id} غير موجود";
                }
                if (str_starts_with($raw, 'Product before update - State: ')) {
                    preg_match('/State: (\d+), Quantity: (\d+)/', $raw, $m);
                    $stateAr = match((int)($m[1] ?? -1)) {
                        0 => 'غير متاح', 1 => 'متاح', 2 => 'محجوز', default => $m[1] ?? '?'
                    };
                    return "حالة المنتج قبل التحديث: {$stateAr}، الكمية: {$m[2]}";
                }
                if (str_starts_with($raw, 'Quantity restored by ')) {
                    preg_match('/by (\d+), new quantity: (\d+)/', $raw, $m);
                    return "تمت استعادة {$m[1]} وحدة، الكمية الجديدة: {$m[2]}";
                }
                if (str_starts_with($raw, 'Updated state based on quantity - new state: ')) {
                    preg_match('/new state: (\d+)/', $raw, $m);
                    $stateAr = match((int)($m[1] ?? -1)) {
                        0 => 'غير متاح', 1 => 'متاح', 2 => 'محجوز', default => $m[1] ?? '?'
                    };
                    return "تم تحديث حالة المنتج إلى: {$stateAr}";
                }
                if (str_starts_with($raw, 'Product saved - Final state: ')) {
                    preg_match('/Final state: (\d+), Final quantity: (\d+)/', $raw, $m);
                    $stateAr = match((int)($m[1] ?? -1)) {
                        0 => 'غير متاح', 1 => 'متاح', 2 => 'محجوز', default => $m[1] ?? '?'
                    };
                    return "تم حفظ المنتج — الحالة: {$stateAr}، الكمية: {$m[2]}";
                }
                if (str_starts_with($raw, 'Sending success email to: ')) {
                    $email = str_replace('Sending success email to: ', '', $raw);
                    return "إرسال بريد التأكيد إلى: {$email}";
                }
                if (str_starts_with($raw, 'ERROR sending email: ')) {
                    $err = str_replace('ERROR sending email: ', '', $raw);
                    return "خطأ أثناء إرسال البريد الإلكتروني: {$err}";
                }
                if (str_starts_with($raw, 'WhatsApp: No phone number found')) {
                    return 'واتساب: لا يوجد رقم هاتف مرتبط بالطلب';
                }
                if (str_starts_with($raw, 'WhatsApp sent to ')) {
                    preg_match('/sent to (.+?) - Status: (.+)/', $raw, $m);
                    $sentAr = ($m[2] ?? '') === 'Success' ? 'ناجح ✓' : 'فشل ✗';
                    return "إرسال رسالة واتساب إلى {$m[1]}: {$sentAr}";
                }
                if (str_starts_with($raw, 'WhatsApp ERROR: ')) {
                    $err = str_replace('WhatsApp ERROR: ', '', $raw);
                    return "خطأ واتساب: {$err}";
                }
                if (str_starts_with($raw, 'Unknown order status: ')) {
                    $val = str_replace('Unknown order status: ', '', $raw);
                    return "حالة دفع غير معروفة: {$val}";
                }
                // fallback – أظهر الرسالة كما هي
                return $raw;
            };

            // ===== ترجمة المراحل =====
            $stepLabels = [
                'START'    => ['label' => 'البداية',     'icon' => 'ti-player-play',   'color' => 'secondary'],
                'STEP 1'   => ['label' => 'المرحلة 1',   'icon' => 'ti-circle-check',  'color' => 'info'],
                'STEP 2'   => ['label' => 'المرحلة 2',   'icon' => 'ti-database-import','color' => 'info'],
                'STEP 3'   => ['label' => 'المرحلة 3',   'icon' => 'ti-shield-lock',   'color' => 'warning'],
                'STEP 4'   => ['label' => 'المرحلة 4',   'icon' => 'ti-search',        'color' => 'primary'],
                'STEP 5'   => ['label' => 'المرحلة 5',   'icon' => 'ti-settings',      'color' => 'primary'],
                'WHATSAPP' => ['label' => 'واتساب',      'icon' => 'ti-brand-whatsapp','color' => 'success'],
                'END'      => ['label' => 'النهاية',     'icon' => 'ti-flag',          'color' => 'secondary'],
            ];

            // ===== ألوان وأيقونات مستوى الحدث =====
            $levelConfig = [
                'info'    => ['color' => '#3B82F6', 'bg' => '#EFF6FF', 'icon' => 'ti-info-circle',    'label' => 'معلومة'],
                'success' => ['color' => '#22C55E', 'bg' => '#F0FDF4', 'icon' => 'ti-circle-check',   'label' => 'نجاح'],
                'warning' => ['color' => '#F59E0B', 'bg' => '#FFFBEB', 'icon' => 'ti-alert-triangle', 'label' => 'تنبيه'],
                'error'   => ['color' => '#EF4444', 'bg' => '#FEF2F2', 'icon' => 'ti-alert-circle',   'label' => 'خطأ'],
            ];

            $firstLog = $order->logs->first();
        @endphp

        <style>
            .order-timeline { position: relative; padding: 0; list-style: none; }
            .order-timeline::before {
                content: '';
                position: absolute;
                right: 22px;
                top: 0;
                bottom: 0;
                width: 2px;
                background: linear-gradient(to bottom, #e2e8f0 0%, #cbd5e1 100%);
            }
            [dir="ltr"] .order-timeline::before { left: 22px; right: auto; }
            .order-timeline-item { position: relative; padding: 0 60px 18px 0; }
            [dir="ltr"] .order-timeline-item { padding: 0 0 18px 60px; }
            .order-timeline-icon {
                position: absolute;
                right: 5px;
                top: 4px;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1rem;
                border: 3px solid #fff;
                box-shadow: 0 1px 4px rgba(0,0,0,.15);
                z-index: 1;
            }
            [dir="ltr"] .order-timeline-icon { right: auto; left: 5px; }
            .order-timeline-card {
                border-radius: 10px;
                border: 1px solid #e2e8f0;
                padding: 10px 14px;
                transition: box-shadow .15s;
            }
            .order-timeline-card:hover { box-shadow: 0 2px 10px rgba(0,0,0,.08); }
            .order-timeline-card.tl-error   { border-color: #fca5a5; }
            .order-timeline-card.tl-warning { border-color: #fde68a; }
            .order-timeline-card.tl-success { border-color: #86efac; }
            .order-timeline-meta { font-size: 0.73rem; color: #94a3b8; }
            .order-timeline-msg  { font-size: 0.88rem; line-height: 1.45; }
            .tl-step-badge {
                display: inline-block;
                font-size: 0.68rem;
                font-weight: 600;
                padding: 1px 8px;
                border-radius: 20px;
                margin-inline-end: 5px;
                vertical-align: middle;
            }
            .tl-confirmation-badge {
                font-size: 0.7rem;
                padding: 1px 7px;
                border-radius: 20px;
                display: inline-flex;
                align-items: center;
                gap: 3px;
            }
        </style>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-timeline me-2 text-primary"></i>سجل مراحل معالجة الطلب
                    <span class="badge bg-label-primary ms-2">{{ $order->logs->count() }} حدث</span>
                </h5>
                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#orderLogsCollapse" aria-expanded="true">
                    <i class="ti ti-chevron-down"></i>
                </button>
            </div>
            <div class="collapse show" id="orderLogsCollapse">
                <div class="card-body pb-2" style="max-height: 650px; overflow-y: auto;">

                    {{-- ملخص العملية في الأعلى --}}
                    @if ($firstLog && $firstLog->process_id)
                        <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded bg-light border" style="font-size:0.78rem; direction:ltr; text-align:left;">
                            <i class="ti ti-fingerprint text-muted"></i>
                            <span class="text-muted">رقم العملية:</span>
                            <code class="text-dark">{{ $firstLog->process_id }}</code>
                            <span class="text-muted ms-auto">IP: {{ $firstLog->ip ?? '—' }}</span>
                        </div>
                    @endif

                    <ul class="order-timeline">
                        @foreach ($order->logs as $i => $log)
                            @php
                                $cfg   = $levelConfig[$log->level] ?? $levelConfig['info'];
                                $step  = $stepLabels[$log->step]  ?? null;
                                $msg   = $translateMessage($log->message);
                                $cardCls = match($log->level) {
                                    'error'   => 'tl-error',
                                    'warning' => 'tl-warning',
                                    'success' => 'tl-success',
                                    default   => '',
                                };
                            @endphp
                            <li class="order-timeline-item">
                                {{-- أيقونة دائرة الجدول الزمني --}}
                                <div class="order-timeline-icon"
                                     style="background-color: {{ $cfg['bg'] }}; color: {{ $cfg['color'] }};">
                                    <i class="ti {{ $cfg['icon'] }}" style="font-size:0.9rem;"></i>
                                </div>

                                <div class="order-timeline-card {{ $cardCls }}"
                                     style="background-color: {{ $cfg['bg'] }};">
                                    <div class="d-flex flex-wrap align-items-start gap-1 mb-1">
                                        {{-- شارة المرحلة --}}
                                        @if ($step)
                                            <span class="tl-step-badge"
                                                  style="background:{{ $cfg['color'] }}22; color:{{ $cfg['color'] }}; border:1px solid {{ $cfg['color'] }}55;">
                                                <i class="ti {{ $step['icon'] }}" style="font-size:0.65rem;"></i>
                                                {{ $step['label'] }}
                                            </span>
                                        @endif

                                        {{-- شارة التأكيد --}}
                                        @if ($log->confirmation_type === 'manual')
                                            <span class="tl-confirmation-badge bg-label-warning">
                                                <i class="ti ti-user-check" style="font-size:0.7rem;"></i> تأكيد يدوي
                                            </span>
                                        @elseif ($log->confirmation_type === 'automatic')
                                            <span class="tl-confirmation-badge bg-label-primary">
                                                <i class="ti ti-robot" style="font-size:0.7rem;"></i> تأكيد تلقائي
                                            </span>
                                        @endif

                                        {{-- التوقيت --}}
                                        <span class="order-timeline-meta ms-auto text-nowrap">
                                            <i class="ti ti-clock me-1"></i>
                                            {{ $log->created_at->format('H:i:s') }} — {{ $log->created_at->format('Y/m/d') }}
                                        </span>
                                    </div>

                                    {{-- الرسالة --}}
                                    <p class="order-timeline-msg mb-0 {{ $log->level === 'error' ? 'fw-semibold text-danger' : '' }}"
                                       style="{{ $log->level !== 'error' ? 'color:#1e293b;' : '' }}">
                                        {{ $msg }}
                                    </p>

                                    {{-- زر البيانات التقنية --}}
                                    @if ($log->context)
                                        <div class="mt-2 text-end">
                                            <button type="button"
                                                    class="btn btn-sm py-0 px-2"
                                                    style="font-size:0.72rem; border:1px solid #cbd5e1; background:#fff; color:#64748b;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#logCtxModal{{ $log->id }}">
                                                <i class="ti ti-code me-1"></i>تفاصيل تقنية
                                            </button>
                                        </div>

                                        {{-- Modal بيانات السياق --}}
                                        <div class="modal fade" id="logCtxModal{{ $log->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title">
                                                            <i class="ti ti-code me-2 text-primary"></i>
                                                            بيانات تقنية —
                                                            @if($step) {{ $step['label'] }} @endif
                                                            ({{ $log->created_at->format('H:i:s') }})
                                                        </h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-0">
                                                        <pre class="mb-0 p-3 bg-dark text-light rounded-bottom"
                                                             style="max-height:420px; overflow-y:auto; font-size:0.78rem; direction:ltr; text-align:left;">{{ json_encode($log->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
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
