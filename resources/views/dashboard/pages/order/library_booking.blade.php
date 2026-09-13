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
                        <div class="row g-2 align-items-end mb-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">ابحث عن الكتاب أو المدرس أو الصف لإضافته للطلب</label>
                                <select id="productSelector" class="form-select select2-products">
                                    <option value="">-- اختر كتاب لإضافته --</option>
                                    @foreach ($products as $product)
                                        @php
                                            $prodPrice = $product->final_price ?? $product->price ?? 0;
                                            $stock = $product->quantity ?? 0;
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
                                            ({{ $prodPrice }} ج.م) - المخزون: {{ $stock }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">الكمية</label>
                                <input type="number" id="quickQuantity" class="form-control text-center" value="1" min="1" max="99">
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="btnAddProduct" class="btn btn-success w-100">
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
                            <i class="ti ti-map-pin me-2 fs-4"></i>فرع الاستلام
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="shipping_method_id">
                                    المكتبة / فرع الاستلام <span class="required-star">*</span>
                                </label>
                                <select name="shipping_method_id" id="shipping_method_id" class="form-select">
                                    @foreach ($shippingMethods as $method)
                                        <option value="{{ $method->id }}" 
                                                data-fee="{{ $method->type === 'branch' ? 0 : ($method->fee ?? 0) }}"
                                                {{ $loop->first ? 'selected' : '' }}>
                                            {{ $method->name }} 
                                            @if($method->type === 'branch') 
                                                (استلام من الفرع - مجاناً)
                                            @else
                                                (شحن: {{ $method->fee ?? 0 }} ج.م)
                                            @endif
                                            @if($method->address) - {{ $method->address }} @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
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
                                <option value="success" selected>🟢 طلب ناجح ومستلم (Success)</option>
                                <option value="reserved">🔵 طلب محجوز (Reserved)</option>
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
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">الخصم:</span>
                            <span class="text-danger fw-semibold" id="discountDisplay">- 0.00 ج.م</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">رسوم الفرع:</span>
                            <span class="text-muted" id="deliveryDisplay">0.00 ج.م</span>
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

        if (selectedItems[productId]) {
            selectedItems[productId].quantity += qtyToAdd;
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
        $('#quickQuantity').val(1);
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

            html += `
                <tr class="item-row" data-id="${item.id}">
                    <td>
                        <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                        <div class="fw-bold text-dark">${item.name}</div>
                        <div class="small text-muted">
                            ${item.brand ? '<span class="badge bg-label-info me-1">مدرس: ' + item.brand + '</span>' : ''}
                            ${item.stage ? '<span class="badge bg-label-secondary me-1">' + item.stage + '</span>' : ''}
                            <span class="badge ${item.stock > 0 ? 'bg-label-success' : 'bg-label-danger'} badge-stock">
                                المتاح: ${item.stock}
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
                            <input type="number" min="1" max="99" 
                                   name="items[${index}][quantity]" 
                                   class="form-control form-control-sm text-center item-qty-input" 
                                   data-id="${item.id}" 
                                   value="${item.quantity}" 
                                   style="width: 55px;">
                            <button type="button" class="btn btn-sm btn-outline-secondary qty-btn btn-increase" data-id="${item.id}">
                                <i class="ti ti-plus"></i>
                            </button>
                        </div>
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
        const val = parseInt($(this).val()) || 1;
        if (selectedItems[id]) {
            selectedItems[id].quantity = Math.max(1, val);
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

    // Shipping, discount, deposit change
    $('#shipping_method_id, #discount, #deposit_amount').on('change input', function() {
        calculateTotals();
    });

    // Calculate totals & balances
    function calculateTotals() {
        let subtotal = 0;
        Object.values(selectedItems).forEach(item => {
            subtotal += (item.price * item.quantity);
        });

        const discount = parseFloat($('#discount').val()) || 0;
        const shippingFee = parseFloat($('#shipping_method_id option:selected').data('fee')) || 0;
        const grandTotal = Math.max(0, (subtotal + shippingFee) - discount);

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
        $('#deliveryDisplay').text(shippingFee.toFixed(2) + ' ج.م');
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

        // Disable submit button to prevent double-submit
        $('#btnSubmitOrder').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>جاري حفظ الطلب...');
    });
});
</script>
@endsection
