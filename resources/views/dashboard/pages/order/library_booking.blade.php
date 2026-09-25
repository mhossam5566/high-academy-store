@extends('dashboard.layouts.layoutMaster')

@section('title', 'الحجز من المكتبة - إنشاء طلب جديد')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
    <style>
        .required-star {
            color: #ea5455;
            font-weight: bold;
        }
        .summary-card {
            position: sticky;
            top: 90px;
        }
        .qty-btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }
        .item-row {
            transition: all 0.2s ease;
        }
        .item-row:hover {
            background-color: rgba(115, 103, 240, 0.04);
        }
        .badge-stock {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .payment-option-card {
            border: 2px solid #e7e7e8;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .payment-option-card:hover {
            border-color: #7367f0;
            background-color: rgba(115, 103, 240, 0.02);
        }
        .payment-option-card.active {
            border-color: #7367f0;
            background-color: rgba(115, 103, 240, 0.06);
        }
        .nav-pills .nav-link {
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .nav-pills .nav-link.active {
            box-shadow: 0 2px 6px rgba(115, 103, 240, 0.35);
        }
        @media print {
            #layout-menu,
            .layout-navbar,
            .content-footer,
            #libraryBookingTabs,
            #quickPeriodGroup,
            #statsFilterForm,
            .btn-print-report,
            #btnExportExcel,
            .btn,
            .card-header .btn-group,
            .badge-primary {
                display: none !important;
            }
            .card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
                page-break-inside: avoid;
            }
            body {
                background-color: #fff !important;
            }
            .content-wrapper {
                padding: 0 !important;
                margin: 0 !important;
            }
        }
    </style>
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <div>
            <h4 class="mb-1 text-primary fw-bold">
                <i class="ti ti-building-store me-2"></i>الحجز من المكتبة
            </h4>
            <p class="text-muted mb-0">إنشاء وتوثيق طلبات الطلاب المباشرة بالمكتبة وحفظها فوراً في النظام</p>
        </div>
        <a href="{{ route('dashboard.orders') }}" class="btn btn-label-secondary">
            <i class="ti ti-arrow-right me-1"></i>العودة لقائمة الطلبات
        </a>
    </div>

    {{-- Navigation Tabs --}}
    <ul class="nav nav-pills mb-4 gap-2" id="libraryBookingTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ ($activeTab ?? 'booking') === 'booking' ? 'active' : '' }} fw-bold px-4 py-2" 
                    id="tab-booking-btn" data-bs-toggle="pill" data-bs-target="#tab-booking-pane" type="button" role="tab" 
                    aria-controls="tab-booking-pane" aria-selected="{{ ($activeTab ?? 'booking') === 'booking' ? 'true' : 'false' }}">
                <i class="ti ti-edit me-2 fs-5"></i>حجز طلب جديد بالمكتبة
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ ($activeTab ?? 'booking') === 'statistics' ? 'active' : '' }} fw-bold px-4 py-2" 
                    id="tab-stats-btn" data-bs-toggle="pill" data-bs-target="#tab-stats-pane" type="button" role="tab" 
                    aria-controls="tab-stats-pane" aria-selected="{{ ($activeTab ?? 'booking') === 'statistics' ? 'true' : 'false' }}">
                <i class="ti ti-chart-bar me-2 fs-5"></i>إحصائيات المبيعات والفروع
                <span class="badge bg-primary ms-2" id="tabStatsBadge">{{ number_format($stats['grand_total_books'] ?? 0) }} كتاب</span>
            </button>
        </li>
    </ul>

    <div class="tab-content p-0 border-0 shadow-none bg-transparent" id="libraryBookingTabsContent">
        {{-- TAB 1: Booking Form --}}
        <div class="tab-pane fade {{ ($activeTab ?? 'booking') === 'booking' ? 'show active' : '' }}" 
             id="tab-booking-pane" role="tabpanel" aria-labelledby="tab-booking-btn">

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible mb-4" role="alert">
            <h6 class="alert-heading fw-bold mb-1"><i class="ti ti-alert-triangle me-1"></i>يرجى تصحيح الأخطاء التالية:</h6>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form id="libraryBookingForm" action="{{ route('dashboard.orders.library_booking.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            {{-- Main Form Left Column --}}
            <div class="col-lg-8">
                {{-- Student Information Card --}}
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-label-primary py-3">
                        <h5 class="card-title mb-0 d-flex align-items-center text-primary">
                            <i class="ti ti-user-check me-2 fs-4"></i>بيانات الطالب الأساسية
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="student_name">
                                    اسم الطالب <span class="required-star">*</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ti ti-user"></i></span>
                                    <input type="text" id="student_name" name="student_name" 
                                           class="form-control" placeholder="أدخل اسم الطالب ثلاثي أو رباعي"
                                           value="{{ old('student_name') }}" required autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="mobile">
                                    رقم هاتف الطالب / الواتساب <span class="required-star">*</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ti ti-phone"></i></span>
                                    <input type="text" id="mobile" name="mobile" 
                                           class="form-control" placeholder="01xxxxxxxxx (11 رقم)"
                                           value="{{ old('mobile') }}" maxlength="11" required autocomplete="off">
                                </div>
                                <small class="text-muted">يتم البحث عن الطالب بهذا الرقم أو إنشاء حساب جديد له تلقائياً</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="temp_mobile">
                                    رقم هاتف إضافي / ولي الأمر <span class="text-muted">(اختياري)</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ti ti-phone-call"></i></span>
                                    <input type="text" id="temp_mobile" name="temp_mobile" 
                                           class="form-control" placeholder="01xxxxxxxxx"
                                           value="{{ old('temp_mobile') }}" maxlength="11" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="notes">
                                    ملاحظات إضافية <span class="text-muted">(اختياري)</span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ti ti-notes"></i></span>
                                    <input type="text" id="notes" name="notes" 
                                           class="form-control" placeholder="أي ملاحظات خاصة بالطلب أو الطالب"
                                           value="{{ old('notes') }}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Books Selection Card --}}
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-label-success py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 d-flex align-items-center text-success">
                            <i class="ti ti-books me-2 fs-4"></i>اختيار الكتب والمنتجات
                        </h5>
                        <span class="badge bg-success" id="selectedItemsCount">0 كتب مختارة</span>
                    </div>
                    <div class="card-body pt-4">
                        {{-- Product Search Selector --}}
                        @if($products->isEmpty())
                            <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                                <i class="ti ti-alert-circle fs-4 me-2"></i>
                                <div>
                                    <strong>تنبيه:</strong> لا توجد كتب مفعلة للحجز المسبق حالياً في النظام (الكتب التي حالتها: <strong>يمكن حجزه</strong>).
                                </div>
                            </div>
                        @endif

                        <div class="row g-2 align-items-end mb-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">ابحث عن كتاب متاح للحجز المسبق (اسم الكتاب / المدرس / الصف)</label>
                                <select id="productSelector" class="form-select select2-products">
                                    <option value="">-- اختر من الكتب المتاحة للحجز المسبق --</option>
                                    @php
                                        $availableProducts = $products->filter(fn($p) => ($p->quantity ?? 0) > 0);
                                        $outOfStockProducts = $products->filter(fn($p) => ($p->quantity ?? 0) <= 0);
                                    @endphp

                                    @if($availableProducts->isNotEmpty())
                                        <optgroup label="✅ الكتب المتوفرة بالمخزن">
                                            @foreach ($availableProducts as $product)
                                                @php
                                                    $prodPrice = $product->final_price ?? $product->price ?? 0;
                                                    $stock = (int) ($product->quantity ?? 0);
                                                    $stage = $product->sliders->name ?? '';
                                                    $brand = $product->brands->name ?? '';
                                                @endphp
                                                <option value="{{ $product->id }}" 
                                                        data-name="{{ $product->name }}"
                                                        data-short-name="{{ $product->short_name ?: $product->name }}"
                                                        data-price="{{ $prodPrice }}"
                                                        data-stock="{{ $stock }}"
                                                        data-brand="{{ $brand }}"
                                                        data-stage="{{ $stage }}">
                                                    {{ $product->short_name ?: $product->name }} 
                                                    @if($brand) - [مدرس: {{ $brand }}] @endif
                                                    @if($stage) - [{{ $stage }}] @endif
                                                    ({{ $prodPrice }} ج.م) - [المتاح: {{ $stock }}]
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif

                                    @if($outOfStockProducts->isNotEmpty())
                                        <optgroup label="❌ كتب نفدت من المخزن (رصيد 0 - غير متاحة للإضافة)">
                                            @foreach ($outOfStockProducts as $product)
                                                @php
                                                    $prodPrice = $product->final_price ?? $product->price ?? 0;
                                                    $stage = $product->sliders->name ?? '';
                                                    $brand = $product->brands->name ?? '';
                                                @endphp
                                                <option value="{{ $product->id }}" 
                                                        data-name="{{ $product->name }}"
                                                        data-short-name="{{ $product->short_name ?: $product->name }}"
                                                        data-price="{{ $prodPrice }}"
                                                        data-stock="0"
                                                        data-brand="{{ $brand }}"
                                                        data-stage="{{ $stage }}"
                                                        disabled>
                                                    {{ $product->short_name ?: $product->name }} 
                                                    @if($brand) - [مدرس: {{ $brand }}] @endif
                                                    @if($stage) - [{{ $stage }}] @endif
                                                    ({{ $prodPrice }} ج.م) - [نفد من المخزن: 0]
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>
                                <div id="stockAvailabilityBadge" class="mt-2" style="display: none;"></div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold" for="quickQuantity">
                                    الكمية <span id="quickMaxStockHint" class="text-primary small fw-bold"></span>
                                </label>
                                <input type="number" id="quickQuantity" class="form-control text-center fw-bold" value="1" min="1" max="99">
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="btnAddProduct" class="btn btn-success w-100" {{ $products->isEmpty() ? 'disabled' : '' }}>
                                    <i class="ti ti-plus me-1"></i>إضافة
                                </button>
                            </div>
                        </div>

                        {{-- Selected Items Table --}}
                        <div class="table-responsive border rounded">
                            <table class="table table-hover align-middle mb-0" id="itemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%;">الكتاب / المنتج</th>
                                        <th style="width: 20%;" class="text-center">سعر الوحدة</th>
                                        <th style="width: 20%;" class="text-center">الكمية</th>
                                        <th style="width: 15%;" class="text-center">الإجمالي</th>
                                        <th style="width: 5%;" class="text-center">حذف</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsTableBody">
                                    <tr id="emptyRow">
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="ti ti-shopping-cart-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                            لم يتم إضافة أي كتب للطلب بعد. اختر كتاباً من القائمة أعلاه واضغط "إضافة".
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Delivery & Pickup Branch Card --}}
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-label-info py-3">
                        <h5 class="card-title mb-0 d-flex align-items-center text-info">
                            <i class="ti ti-building me-2 fs-4"></i>فرع المكتبة للاستلام
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        <label class="form-label fw-bold mb-2">اختر فرع المكتبة الذي سيتم استلام الكتب منه <span class="required-star">*</span></label>
                        <div class="row g-3">
                            @foreach ($shippingMethods as $method)
                                <div class="col-md-6">
                                    <div class="form-check custom-option custom-option-basic p-3 border rounded h-100 {{ $loop->first ? 'border-primary bg-label-primary' : '' }}">
                                        <input class="form-check-input" type="radio" name="shipping_method_id" id="branch_{{ $method->id }}" value="{{ $method->id }}" {{ $loop->first ? 'checked' : '' }} required>
                                        <label class="form-check-label w-100 cursor-pointer" for="branch_{{ $method->id }}">
                                            <span class="d-block fw-bold text-dark fs-6">{{ $method->name }}</span>
                                            @if($method->address)
                                                <small class="text-muted d-block mt-1"><i class="ti ti-map-pin me-1"></i>{{ $method->address }}</small>
                                            @endif
                                            <span class="badge bg-label-success mt-2">استلام مباشر من الفرع</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Summary & Payment Right Column --}}
            <div class="col-lg-4">
                <div class="card summary-card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="card-title mb-0 text-white d-flex align-items-center">
                            <i class="ti ti-cash me-2 fs-4"></i>الدفع بالمكتبة كاش
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        {{-- Payment Method Notice --}}
                        <div class="alert alert-label-primary py-2 px-3 mb-3 d-flex align-items-center">
                            <i class="ti ti-cash me-2 fs-5"></i>
                            <span class="fw-semibold">وسيلة الدفع: كاش بالمكتبة</span>
                        </div>

                        {{-- Payment Type Selector --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">نظام الدفع <span class="required-star">*</span></label>
                            
                            <div class="d-flex flex-column gap-2">
                                <label class="payment-option-card active" for="pay_full">
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input me-2" type="radio" name="payment_type" id="pay_full" value="full" checked>
                                        <div>
                                            <div class="fw-bold text-dark">دفع كامل المبلغ كاش</div>
                                            <small class="text-muted">تحصيل كامل قيمة الطلب الآن</small>
                                        </div>
                                    </div>
                                </label>

                                <label class="payment-option-card" for="pay_deposit">
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input me-2" type="radio" name="payment_type" id="pay_deposit" value="deposit">
                                        <div>
                                            <div class="fw-bold text-primary">دفع عربون جزئي</div>
                                            <small class="text-muted">دفع جزء من المبلغ والباقي عند الاستلام</small>
                                        </div>
                                    </div>
                                </label>

                                <label class="payment-option-card" for="pay_later">
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input me-2" type="radio" name="payment_type" id="pay_later" value="later">
                                        <div>
                                            <div class="fw-bold text-secondary">حجز بدون دفع الآن</div>
                                            <small class="text-muted">دفع كامل المبلغ عند الاستلام بالمكتبة</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Deposit Amount Input (Shown when deposit is selected) --}}
                        <div class="mb-3" id="depositSection" style="display: none;">
                            <label class="form-label fw-bold text-primary" for="deposit_amount">
                                قيمة العربون المدفوع (جنيه) <span class="required-star">*</span>
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-cash"></i></span>
                                <input type="number" id="deposit_amount" name="deposit_amount" 
                                       class="form-control form-control-lg fw-bold text-primary" 
                                       placeholder="أدخل قيمة العربون" min="1" step="1" value="50">
                                <span class="input-group-text">ج.م</span>
                            </div>
                            <small class="text-muted">المبلغ الذي قام الطالب بدفعه في المكتبة كعربون حجز</small>
                        </div>

                        {{-- Order Status --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="status">
                                حالة الطلب
                            </label>
                            <select name="status" id="status" class="form-select">
                                <option value="reserved" selected>🔵 طلب محجوز (Reserved)</option>
                                <option value="success">🟢 طلب ناجح ومستلم (Success)</option>
                                <option value="new">🟡 طلب جديد (New)</option>
                            </select>
                        </div>

                        {{-- Discount Input --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="discount">
                                خصم إضافي (جنيه) <span class="text-muted">(اختياري)</span>
                            </label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-discount"></i></span>
                                <input type="number" id="discount" name="discount" 
                                       class="form-control" placeholder="0" min="0" step="0.5"
                                       value="{{ old('discount', 0) }}">
                                <span class="input-group-text">ج.م</span>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Financial Breakdown --}}
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">مجموع الكتب:</span>
                            <span class="fw-semibold" id="subtotalDisplay">0.00 ج.م</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">الخصم:</span>
                            <span class="text-danger fw-semibold" id="discountDisplay">- 0.00 ج.م</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center bg-label-primary p-2 rounded mb-3">
                            <span class="fw-bold text-primary">المبلغ الإجمالي المطلوب:</span>
                            <span class="fw-bold fs-5 text-primary" id="grandTotalDisplay">0.00 ج.م</span>
                        </div>

                        {{-- Paid vs Remaining Box --}}
                        <div class="border rounded p-3 mb-4 bg-light">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-success fw-bold"><i class="ti ti-circle-check me-1"></i>المدفوع الآن:</span>
                                <span class="fw-bold text-success fs-6" id="paidDisplay">0.00 ج.م</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-danger fw-bold"><i class="ti ti-clock me-1"></i>المتبقي عند الاستلام:</span>
                                <span class="fw-bold text-danger fs-6" id="remainingDisplay">0.00 ج.م</span>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" id="btnSubmitOrder" class="btn btn-primary btn-lg w-100 shadow-sm py-3 fw-bold">
                            <i class="ti ti-check me-2 fs-4"></i>تأكيد وحفظ الطلب
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
        </div> {{-- End Tab 1 Booking Pane --}}

        {{-- TAB 2: Statistics & Reports --}}
        <div class="tab-pane fade {{ ($activeTab ?? 'booking') === 'statistics' ? 'show active' : '' }}" 
             id="tab-stats-pane" role="tabpanel" aria-labelledby="tab-stats-btn">
            
            {{-- Filter Toolbar Card --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                        <i class="ti ti-filter me-2 text-primary"></i>تحديد دورة وفترة الإحصائيات
                    </h5>
                    {{-- Quick Period Presets --}}
                    <div class="btn-group btn-group-sm mt-2 mt-sm-0" role="group" id="quickPeriodGroup">
                        <button type="button" class="btn btn-outline-primary {{ ($stats['period'] ?? '') === 'today' ? 'active' : '' }}" data-period="today">
                            <i class="ti ti-calendar-event me-1"></i>اليوم
                        </button>
                        <button type="button" class="btn btn-outline-primary {{ ($stats['period'] ?? '') === 'week' ? 'active' : '' }}" data-period="week">
                            <i class="ti ti-calendar me-1"></i>هذا الأسبوع
                        </button>
                        <button type="button" class="btn btn-outline-primary {{ ($stats['period'] ?? 'month') === 'month' ? 'active' : '' }}" data-period="month">
                            <i class="ti ti-calendar-month me-1"></i>هذا الشهر
                        </button>
                        <button type="button" class="btn btn-outline-primary {{ ($stats['period'] ?? '') === 'year' ? 'active' : '' }}" data-period="year">
                            <i class="ti ti-calendar-time me-1"></i>هذه السنة
                        </button>
                        <button type="button" class="btn btn-outline-primary {{ ($stats['period'] ?? '') === 'custom' ? 'active' : '' }}" data-period="custom">
                            <i class="ti ti-calendar-stats me-1"></i>فترة مخصصة
                        </button>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <form id="statsFilterForm" class="row g-3 align-items-end">
                        <input type="hidden" name="period" id="statsPeriodInput" value="{{ $stats['period'] ?? 'month' }}">

                        {{-- Custom Date Inputs --}}
                        <div class="col-md-3 col-sm-6" id="fromDateCol" style="{{ ($stats['period'] ?? '') === 'custom' ? '' : 'display: none;' }}">
                            <label class="form-label fw-semibold" for="stats_from_date">من تاريخ</label>
                            <input type="date" id="stats_from_date" name="from_date" class="form-control" value="{{ $stats['from_date'] ?? '' }}">
                        </div>
                        <div class="col-md-3 col-sm-6" id="toDateCol" style="{{ ($stats['period'] ?? '') === 'custom' ? '' : 'display: none;' }}">
                            <label class="form-label fw-semibold" for="stats_to_date">إلى تاريخ</label>
                            <input type="date" id="stats_to_date" name="to_date" class="form-control" value="{{ $stats['to_date'] ?? '' }}">
                        </div>

                        {{-- Branch Filter --}}
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold" for="stats_branch_id">فرع المكتبة</label>
                            <select name="branch_id" id="stats_branch_id" class="form-select">
                                <option value="all">-- كل الفروع --</option>
                                @foreach($shippingMethods as $b)
                                    <option value="{{ $b->id }}" {{ ($stats['selected_branch_id'] ?? '') == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Product Filter --}}
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold" for="stats_product_id">تصفية حسب الكتاب / الصنف</label>
                            <select name="product_id" id="stats_product_id" class="form-select select2-stats-product">
                                <option value="all">-- جميع الكتب والأصناف --</option>
                                @foreach($allProducts ?? $products as $prod)
                                    <option value="{{ $prod->id }}" {{ ($stats['selected_product_id'] ?? '') == $prod->id ? 'selected' : '' }}>
                                        {{ $prod->short_name ?: $prod->name }}
                                        @if($prod->brands) ({{ $prod->brands->title ?? $prod->brands->name }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div class="col-md-2 col-sm-6">
                            <label class="form-label fw-semibold" for="stats_status">حالة الطلبات</label>
                            <select name="status" id="stats_status" class="form-select">
                                <option value="all" {{ ($stats['selected_status'] ?? '') == 'all' ? 'selected' : '' }}>كل الحالات المعتمدة</option>
                                <option value="reserved" {{ ($stats['selected_status'] ?? '') == 'reserved' ? 'selected' : '' }}>محجوز (Reserved)</option>
                                <option value="success" {{ ($stats['selected_status'] ?? '') == 'success' ? 'selected' : '' }}>ناجح ومستلم (Success)</option>
                                <option value="new" {{ ($stats['selected_status'] ?? '') == 'new' ? 'selected' : '' }}>جديد (New)</option>
                                <option value="pending" {{ ($stats['selected_status'] ?? '') == 'pending' ? 'selected' : '' }}>معلق (Pending)</option>
                            </select>
                        </div>

                        {{-- Filter Action Buttons --}}
                        <div class="col-md-4 col-sm-12 d-flex gap-2">
                            <button type="submit" id="btnApplyStatsFilter" class="btn btn-primary flex-grow-1">
                                <i class="ti ti-search me-1"></i>تطبيق الفلتر
                            </button>
                            <button type="button" id="btnResetStatsFilter" class="btn btn-label-secondary" title="إعادة تعيين إلى هذا الشهر">
                                <i class="ti ti-refresh me-1"></i>إعادة تعيين
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Dynamic Stats Data Wrapper with Loading Overlay --}}
            <div id="statsDataWrapper" class="position-relative">
                <div id="statsLoadingOverlay" class="position-absolute w-100 h-100 top-0 start-0 d-flex justify-content-center align-items-center bg-white bg-opacity-75 rounded" style="display: none; z-index: 10;">
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary mb-2" role="status" style="width: 3rem; height: 3rem;"></div>
                        <div class="fw-bold text-dark fs-6">جاري جلب إحصائيات المبيعات والأصناف...</div>
                    </div>
                </div>
                
                <div id="statsContentPlaceholder">
                    @include('dashboard.pages.order.partials.library_stats_content', ['stats' => $stats])
                </div>
            </div>
        </div> {{-- End Tab 2 Stats Pane --}}
    </div> {{-- End Tab Content --}}
@endsection

@section('page-script')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2-products').select2({
        placeholder: '-- ابحث عن كتاب أو مدرس أو صف --',
        allowClear: true,
        width: '100%',
        dir: 'rtl'
    });

    let selectedItems = {};

    // Payment Type Radio Handlers
    $('input[name="payment_type"]').on('change', function() {
        $('.payment-option-card').removeClass('active');
        $(this).closest('.payment-option-card').addClass('active');

        const val = $(this).val();
        if (val === 'deposit') {
            $('#depositSection').slideDown(200);
            $('#status').val('reserved');
        } else if (val === 'later') {
            $('#depositSection').slideUp(200);
            $('#status').val('reserved');
        } else {
            $('#depositSection').slideUp(200);
            $('#status').val('success');
        }
        calculateTotals();
    });

    // Stock feedback when selecting a product
    $('#productSelector').on('change', function() {
        const select = $(this);
        const option = select.find('option:selected');
        const stock = parseInt(option.data('stock')) || 0;
        const val = select.val();
        const hint = $('#quickMaxStockHint');
        const badge = $('#stockAvailabilityBadge');
        const quickQty = $('#quickQuantity');
        const btnAdd = $('#btnAddProduct');

        if (!val) {
            hint.text('');
            badge.hide();
            quickQty.attr('max', 99).val(1);
            btnAdd.prop('disabled', false);
            return;
        }

        if (stock <= 0) {
            hint.text('(نفد)').removeClass('text-primary').addClass('text-danger');
            quickQty.attr('max', 0).val(0);
            btnAdd.prop('disabled', true);
            badge.html('<span class="badge bg-label-danger py-2 px-3 fs-6 d-inline-flex align-items-center"><i class="ti ti-alert-triangle me-1"></i>عفواً، هذا الكتاب غير متوفر في المخزن حالياً (الكمية: 0). لا يمكن إضافته للطلب.</span>').slideDown(200);
        } else {
            hint.text(`(أقصى حد: ${stock})`).removeClass('text-danger').addClass('text-primary');
            quickQty.attr('max', stock).val(1);
            btnAdd.prop('disabled', false);
            badge.html(`<span class="badge bg-label-success py-2 px-3 fs-6 d-inline-flex align-items-center"><i class="ti ti-check me-1"></i>الكمية المتاحة في المخزن: <strong class="ms-1">${stock}</strong> نسخة</span>`).slideDown(200);
        }
    });

    // Clamp quickQuantity input to available stock
    $('#quickQuantity').on('input change', function() {
        const select = $('#productSelector');
        const option = select.find('option:selected');
        if (select.val()) {
            const stock = parseInt(option.data('stock')) || 0;
            let val = parseInt($(this).val()) || 0;
            if (stock <= 0) {
                $(this).val(0);
            } else if (val > stock) {
                $(this).val(stock);
                Swal.fire({
                    icon: 'warning',
                    title: 'تجاوز الكمية المتاحة',
                    text: `الكمية المتاحة في المخزن لهذا الكتاب هي (${stock}) فقط. تم ضبط الكمية على الحد الأقصى.`,
                    confirmButtonText: 'حسناً',
                    customClass: { confirmButton: 'btn btn-primary' }
                });
            } else if (val < 1) {
                $(this).val(1);
            }
        }
    });

    // Add Product button click
    $('#btnAddProduct').on('click', function() {
        const select = $('#productSelector');
        const productId = select.val();
        if (!productId) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'يرجى اختيار كتاب أولاً من القائمة.',
                confirmButtonText: 'حسناً',
                customClass: { confirmButton: 'btn btn-primary' }
            });
            return;
        }

        const option = select.find('option:selected');
        const name = option.data('short-name') || option.data('name');
        const brand = option.data('brand');
        const stage = option.data('stage');
        const price = parseFloat(option.data('price')) || 0;
        const stock = parseInt(option.data('stock')) || 0;
        const qtyToAdd = parseInt($('#quickQuantity').val()) || 1;

        if (stock <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'غير متوفر بالمخزن',
                text: `الكتاب "${name}" رصيده بالمخزن هو (0) حالياً ولا يمكن إضافته للطلب.`,
                confirmButtonText: 'حسناً',
                customClass: { confirmButton: 'btn btn-danger' }
            });
            return;
        }

        const currentQty = selectedItems[productId] ? selectedItems[productId].quantity : 0;
        const totalQty = currentQty + qtyToAdd;

        if (totalQty > stock) {
            const remainingCanAdd = Math.max(0, stock - currentQty);
            Swal.fire({
                icon: 'warning',
                title: 'تجاوز الكمية المتاحة',
                html: `
                    <div class="text-start">
                        <p class="mb-2">الكمية المطلوبة تتجاوز رصيد المخزن للكتاب: <strong>${name}</strong></p>
                        <ul class="mb-1 text-muted small">
                            <li>الرصيد المتاح بالمخزن: <strong class="text-dark">${stock}</strong> نسخة</li>
                            <li>المضاف حالياً للطلب: <strong class="text-dark">${currentQty}</strong> نسخة</li>
                            <li>أقصى كمية متبقية يمكنك إضافتها: <strong class="text-primary">${remainingCanAdd}</strong> نسخة</li>
                        </ul>
                    </div>
                `,
                confirmButtonText: 'حسناً',
                customClass: { confirmButton: 'btn btn-primary' }
            });
            return;
        }

        if (selectedItems[productId]) {
            selectedItems[productId].quantity = totalQty;
        } else {
            selectedItems[productId] = {
                id: productId,
                name: name,
                brand: brand,
                stage: stage,
                price: price,
                stock: stock,
                quantity: qtyToAdd
            };
        }

        renderItemsTable();
        calculateTotals();

        // Reset selector
        select.val('').trigger('change');
        $('#quickQuantity').val(1).attr('max', 99);
        $('#quickMaxStockHint').text('');
        $('#stockAvailabilityBadge').hide();
    });

    // Render items table
    function renderItemsTable() {
        const tbody = $('#itemsTableBody');
        const keys = Object.keys(selectedItems);

        $('#selectedItemsCount').text(keys.length + ' كتب مختارة');

        if (keys.length === 0) {
            tbody.html(`
                <tr id="emptyRow">
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="ti ti-shopping-cart-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                        لم يتم إضافة أي كتب للطلب بعد. اختر كتاباً من القائمة أعلاه واضغط "إضافة".
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        let index = 0;

        keys.forEach(id => {
            const item = selectedItems[id];
            const itemTotal = (item.price * item.quantity).toFixed(2);
            const isMaxReached = (item.quantity >= item.stock);

            html += `
                <tr class="item-row" data-id="${item.id}">
                    <td>
                        <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                        <div class="fw-bold text-dark">${item.name}</div>
                        <div class="small text-muted">
                            ${item.brand ? '<span class="badge bg-label-info me-1">مدرس: ' + item.brand + '</span>' : ''}
                            ${item.stage ? '<span class="badge bg-label-secondary me-1">' + item.stage + '</span>' : ''}
                            <span class="badge ${item.stock > 0 ? 'bg-label-success' : 'bg-label-danger'} badge-stock">
                                المتاح في المخزن: ${item.stock}
                            </span>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="input-group input-group-sm justify-content-center" style="max-width: 120px; margin: 0 auto;">
                            <input type="number" step="0.5" min="0" 
                                   name="items[${index}][price]" 
                                   class="form-control text-center item-price-input" 
                                   data-id="${item.id}" 
                                   value="${item.price}">
                            <span class="input-group-text">ج.م</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <button type="button" class="btn btn-sm btn-outline-secondary qty-btn btn-decrease" data-id="${item.id}">
                                <i class="ti ti-minus"></i>
                            </button>
                            <input type="number" min="1" max="${item.stock}" 
                                   name="items[${index}][quantity]" 
                                   class="form-control form-control-sm text-center item-qty-input fw-bold" 
                                   data-id="${item.id}" 
                                   value="${item.quantity}" 
                                   style="width: 60px;">
                            <button type="button" class="btn btn-sm ${isMaxReached ? 'btn-label-secondary' : 'btn-outline-secondary'} qty-btn btn-increase" data-id="${item.id}" ${isMaxReached ? 'title="تم الوصول لأقصى كمية متاحة بالمخزن"' : ''}>
                                <i class="ti ti-plus"></i>
                            </button>
                        </div>
                        ${isMaxReached ? '<small class="text-danger d-block mt-1 font-monospace" style="font-size: 0.7rem;">(الحد الأقصى)</small>' : ''}
                    </td>
                    <td class="text-center fw-bold text-primary">
                        <span class="row-total">${itemTotal}</span> ج.م
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-icon btn-label-danger btn-remove-item" data-id="${item.id}" title="حذف الكتاب">
                            <i class="ti ti-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            index++;
        });

        tbody.html(html);
    }

    // Item quantity / price change events
    $(document).on('click', '.btn-increase', function() {
        const id = $(this).data('id');
        if (selectedItems[id]) {
            const stock = selectedItems[id].stock;
            if (selectedItems[id].quantity >= stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'الحد الأقصى للمخزون',
                    text: `لا يمكنك زيادة الكمية. الرصيد المتاح في المخزن لهذا الكتاب هو (${stock}) نسخة فقط.`,
                    confirmButtonText: 'حسناً',
                    customClass: { confirmButton: 'btn btn-primary' }
                });
                return;
            }
            selectedItems[id].quantity += 1;
            renderItemsTable();
            calculateTotals();
        }
    });

    $(document).on('click', '.btn-decrease', function() {
        const id = $(this).data('id');
        if (selectedItems[id] && selectedItems[id].quantity > 1) {
            selectedItems[id].quantity -= 1;
            renderItemsTable();
            calculateTotals();
        }
    });

    $(document).on('change input', '.item-qty-input', function() {
        const id = $(this).data('id');
        let val = parseInt($(this).val()) || 1;
        if (selectedItems[id]) {
            const stock = selectedItems[id].stock;
            if (val > stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تجاوز الكمية المتاحة',
                    text: `الكمية المدخلة (${val}) تتجاوز المتاح في المخزن (${stock}). تم تعديل الكمية تلقائياً إلى الحد الأقصى (${stock}).`,
                    confirmButtonText: 'حسناً',
                    customClass: { confirmButton: 'btn btn-primary' }
                });
                val = stock;
                $(this).val(val);
            }
            if (val < 1) {
                val = 1;
                $(this).val(1);
            }
            selectedItems[id].quantity = val;
            renderItemsTable();
            calculateTotals();
        }
    });

    $(document).on('change input', '.item-price-input', function() {
        const id = $(this).data('id');
        const val = parseFloat($(this).val()) || 0;
        if (selectedItems[id]) {
            selectedItems[id].price = Math.max(0, val);
            calculateTotals();
        }
    });

    $(document).on('click', '.btn-remove-item', function() {
        const id = $(this).data('id');
        delete selectedItems[id];
        renderItemsTable();
        calculateTotals();
    });

    // Branch Radio Handlers
    $('input[name="shipping_method_id"]').on('change', function() {
        $('input[name="shipping_method_id"]').closest('.custom-option').removeClass('border-primary bg-label-primary');
        $(this).closest('.custom-option').addClass('border-primary bg-label-primary');
    });

    // Shipping, discount, deposit change
    $('#discount, #deposit_amount').on('change input', function() {
        calculateTotals();
    });

    // Calculate totals & balances
    function calculateTotals() {
        let subtotal = 0;
        Object.values(selectedItems).forEach(item => {
            subtotal += (item.price * item.quantity);
        });

        const discount = parseFloat($('#discount').val()) || 0;
        const grandTotal = Math.max(0, subtotal - discount);

        const paymentType = $('input[name="payment_type"]:checked').val() || 'full';
        let paidAmount = 0;
        let remainingAmount = 0;

        if (paymentType === 'full') {
            paidAmount = grandTotal;
            remainingAmount = 0;
        } else if (paymentType === 'deposit') {
            const enteredDeposit = parseFloat($('#deposit_amount').val()) || 0;
            paidAmount = Math.min(grandTotal, Math.max(0, enteredDeposit));
            remainingAmount = Math.max(0, grandTotal - paidAmount);
        } else {
            // later / no deposit
            paidAmount = 0;
            remainingAmount = grandTotal;
        }

        $('#subtotalDisplay').text(subtotal.toFixed(2) + ' ج.م');
        $('#discountDisplay').text('- ' + discount.toFixed(2) + ' ج.م');
        $('#grandTotalDisplay').text(grandTotal.toFixed(2) + ' ج.م');
        $('#paidDisplay').text(paidAmount.toFixed(2) + ' ج.م');
        $('#remainingDisplay').text(remainingAmount.toFixed(2) + ' ج.م');
    }

    // Form submission validation
    $('#libraryBookingForm').on('submit', function(e) {
        const keys = Object.keys(selectedItems);
        if (keys.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'تنبيه',
                text: 'يجب اختيار وإضافة كتاب واحد على الأقل للطلب قبل الحفظ.',
                confirmButtonText: 'حسناً',
                customClass: { confirmButton: 'btn btn-primary' }
            });
            return false;
        }

        const studentName = $('#student_name').val().trim();
        const mobile = $('#mobile').val().trim();

        if (!studentName || !mobile) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'بيانات غير مكتملة',
                text: 'يرجى التأكد من كتابة اسم الطالب ورقم الهاتف بشكل صحيح.',
                confirmButtonText: 'حسناً',
                customClass: { confirmButton: 'btn btn-primary' }
            });
            return false;
        }

        // Validate stock for all selected items before sending
        let stockError = null;
        Object.values(selectedItems).forEach(item => {
            if (item.stock <= 0) {
                stockError = `الكتاب "${item.name}" رصيده بالمخزن (0) ولا يمكن إتمام الحجز به. يرجى حذفه أولاً.`;
            } else if (item.quantity > item.stock) {
                stockError = `الكمية المطلوبة من كتاب "${item.name}" (${item.quantity} نسخة) تتجاوز الرصيد المتاح بالمخزن (${item.stock} نسخة).`;
            }
        });

        if (stockError) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'خطأ في الكميات والمخزون',
                text: stockError,
                confirmButtonText: 'حسناً',
                customClass: { confirmButton: 'btn btn-danger' }
            });
            return false;
        }

        // Disable submit button to prevent double-submit
        $('#btnSubmitOrder').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>جاري حفظ الطلب...');
    });

    // ==========================================
    // LIBRARY STATISTICS TAB LOGIC
    // ==========================================

    // Initialize Select2 for stats product filter
    $('.select2-stats-product').select2({
        placeholder: '-- جميع الكتب والأصناف --',
        allowClear: true,
        width: '100%',
        dir: 'rtl'
    });

    // Handle Quick Period Preset Buttons (اليوم، هذا الأسبوع، هذا الشهر، هذه السنة، فترة مخصصة)
    $('#quickPeriodGroup button').on('click', function() {
        const period = $(this).data('period');
        $('#quickPeriodGroup button').removeClass('active');
        $(this).addClass('active');
        $('#statsPeriodInput').val(period);

        if (period === 'custom') {
            $('#fromDateCol, #toDateCol').slideDown(200);
            $('#stats_from_date').focus();
        } else {
            $('#fromDateCol, #toDateCol').slideUp(200);
            // Automatically submit filter when selecting a quick preset
            fetchStatistics();
        }
    });

    // Filter Form Submit
    $('#statsFilterForm').on('submit', function(e) {
        e.preventDefault();
        fetchStatistics();
    });

    // Reset Filter Button
    $('#btnResetStatsFilter').on('click', function() {
        $('#statsPeriodInput').val('month');
        $('#quickPeriodGroup button').removeClass('active');
        $('#quickPeriodGroup button[data-period="month"]').addClass('active');
        $('#fromDateCol, #toDateCol').hide();
        $('#stats_from_date').val('');
        $('#stats_to_date').val('');
        $('#stats_branch_id').val('all');
        $('#stats_product_id').val('all').trigger('change');
        $('#stats_status').val('all');
        fetchStatistics();
    });

    // Function to fetch statistics via AJAX
    function fetchStatistics() {
        const formData = $('#statsFilterForm').serialize();
        $('#statsLoadingOverlay').show();

        // Update Excel export link with current filters
        const exportBaseUrl = "{{ route('dashboard.orders.library_booking.stats.export') }}";
        $('#btnExportExcel').attr('href', exportBaseUrl + '?' + formData);

        $.ajax({
            url: "{{ route('dashboard.orders.library_booking.stats') }}",
            type: "GET",
            data: formData,
            dataType: "json",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#statsLoadingOverlay').hide();
                if (response.status === 'success' && response.html) {
                    $('#statsContentPlaceholder').html(response.html);
                    if (response.stats) {
                        $('#tabStatsBadge').text(response.stats.grand_total_books + ' كتاب');
                        $('#currentPeriodTitle').text(response.stats.period_label);
                        // Re-bind export link inside dynamic content
                        $('#btnExportExcel').attr('href', exportBaseUrl + '?' + formData);
                    }
                }
            },
            error: function(xhr) {
                $('#statsLoadingOverlay').hide();
                console.error('Error fetching statistics:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء تحميل بيانات الإحصائيات، يرجى المحاولة مرة أخرى.',
                    confirmButtonText: 'حسناً',
                    customClass: { confirmButton: 'btn btn-primary' }
                });
            }
        });
    }

    // Persist active tab across page refresh & URL hash
    function checkUrlTab() {
        const hash = window.location.hash;
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');

        if (hash === '#statistics' || tabParam === 'statistics') {
            const statsTab = new bootstrap.Tab(document.getElementById('tab-stats-btn'));
            statsTab.show();
        }
    }

    checkUrlTab();

    $('#tab-booking-btn').on('shown.bs.tab', function() {
        window.history.replaceState(null, null, window.location.pathname + '?tab=booking');
    });

    $('#tab-stats-btn').on('shown.bs.tab', function() {
        window.history.replaceState(null, null, window.location.pathname + '?tab=statistics');
    });
});
</script>
@endsection
