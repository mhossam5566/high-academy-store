@extends('user.layouts.master')
@section('title')
    صفحه الدفع
@endsection

@php
    $discountSetting = DB::table('discount_settings')->first();
@endphp

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        :root {
            --primary: #1a1a2e;
            --accent: #e07b39;
            --accent-light: #f59a5a;
            --surface: #ffffff;
            --surface-alt: #f7f7f9;
            --border: #e8e8ef;
            --text-main: #1a1a2e;
            --text-muted: #7b7b96;
            --success: #22c55e;
            --radius: 18px;
            --radius-sm: 10px;
            --shadow: 0 4px 28px rgba(26, 26, 46, 0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Cairo', sans-serif !important;
            background: #f0f0f5;
            color: var(--text-main);
        }

        .hidden {
            display: none !important;
        }

        /* ─── Wrapper ─── */
        .checkout-wrapper {
            max-width: 860px;
            margin: 0 auto;
            padding: 100px 20px 60px;
        }

        /* ─── Flash ─── */
        .flash-alert {
            border-radius: var(--radius-sm);
            padding: 12px 18px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .flash-success {
            background: #f0fdf4;
            border: 1.5px solid #bbf7d0;
            color: #166534;
        }

        .flash-error {
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            color: #991b1b;
        }

        /* ─── Progress Bar ─── */
        .stepper-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 36px;
            direction: rtl;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            position: relative;
            flex: 1;
        }

        .step-item:not(:last-child)::before {
            content: '';
            position: absolute;
            top: 18px;
            left: 0;
            right: 50%;
            height: 2px;
            background: var(--border);
            z-index: 0;
            transition: background .4s;
        }

        .step-item:not(:first-child)::after {
            content: '';
            position: absolute;
            top: 18px;
            right: 0;
            left: 50%;
            height: 2px;
            background: var(--border);
            z-index: 0;
            transition: background .4s;
        }

        .step-item.done::before,
        .step-item.done::after,
        .step-item.active::after {
            background: var(--accent);
        }

        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--surface);
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-muted);
            z-index: 1;
            transition: all .3s;
            position: relative;
        }

        .step-item.active .step-circle {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
            box-shadow: 0 0 0 5px rgba(224, 123, 57, .18);
        }

        .step-item.done .step-circle {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .step-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-align: center;
            white-space: nowrap;
        }

        .step-item.active .step-label {
            color: var(--accent);
        }

        .step-item.done .step-label {
            color: var(--primary);
        }

        /* ─── Card ─── */
        .step-card {
            background: var(--surface);
            border-radius: var(--radius);
            border: 1.5px solid var(--border);
            box-shadow: var(--shadow);
            padding: 32px 32px 28px;
            animation: fadeUp .35s ease both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .step-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            direction: rtl;
        }

        .step-card-header .icon-circle {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(224, 123, 57, .25);
        }

        .step-card-header h2 {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--primary);
            margin: 0;
        }

        .step-card-header p {
            font-size: 13px;
            color: var(--text-muted);
            margin: 0;
        }

        /* ─── Summary Sidebar (sticky top card) ─── */
        .order-mini-bar {
            background: var(--primary);
            color: #fff;
            border-radius: var(--radius);
            padding: 16px 24px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            direction: rtl;
            flex-wrap: wrap;
            gap: 10px;
        }

        .order-mini-bar .mini-label {
            font-size: 13px;
            opacity: .7;
        }

        .order-mini-bar .mini-val {
            font-size: 18px;
            font-weight: 800;
            color: var(--accent-light);
        }

        /* ─── Product Table ─── */
        .product-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            direction: rtl;
        }

        .product-table thead th {
            background: var(--surface-alt);
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 700;
            padding: 11px 14px;
            border-bottom: 1.5px solid var(--border);
        }

        .product-table thead th:first-child {
            border-radius: 0 var(--radius-sm) 0 0;
        }

        .product-table thead th:last-child {
            border-radius: var(--radius-sm) 0 0 0;
        }

        .product-table tbody tr:hover {
            background: var(--surface-alt);
        }

        .product-table tbody td {
            padding: 13px 14px;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }

        .product-table tbody tr:last-child td {
            border-bottom: none;
        }

        .product-name-link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: color .15s;
        }

        .product-name-link:hover {
            color: var(--accent);
        }

        .qty-badge {
            display: inline-block;
            background: var(--surface-alt);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 2px 12px;
            font-size: 13px;
            font-weight: 700;
        }

        .price-tag {
            font-weight: 700;
            color: var(--accent);
        }

        /* ─── Form Controls ─── */
        .form-label-custom {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 7px;
            display: block;
        }

        .form-control-custom {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
            color: var(--text-main);
            background: var(--surface);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            appearance: none;
            -webkit-appearance: none;
        }

        .form-control-custom:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(224, 123, 57, .13);
        }

        .form-control-custom:disabled {
            background: var(--surface-alt);
            cursor: not-allowed;
        }

        select.form-control-custom {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%237b7b96' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 14px center;
            padding-left: 36px;
        }

        .form-group-custom {
            margin-bottom: 16px;
        }

        /* ─── Shipping Info ─── */
        .ship-info-box {
            background: var(--surface-alt);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 14px 18px;
            margin-top: 16px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            padding: 7px 0;
            border-bottom: 1px dashed #e0e0ec;
            direction: rtl;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row .info-label {
            color: var(--text-muted);
            font-weight: 600;
        }

        .info-row .info-value {
            font-weight: 700;
        }

        /* ─── Summary Rows ─── */
        .summary-lines {
            direction: rtl;
        }

        .sum-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }

        .sum-row:last-child {
            border-bottom: none;
        }

        .sum-row .s-lbl {
            color: var(--text-muted);
            font-weight: 600;
        }

        .sum-row .s-val {
            font-weight: 700;
        }

        .sum-row.total {
            padding-top: 16px;
            margin-top: 4px;
            border-top: 2px solid var(--primary);
        }

        .sum-row.total .s-lbl {
            font-size: 17px;
            font-weight: 800;
            color: var(--primary);
        }

        .sum-row.total .s-val {
            font-size: 22px;
            font-weight: 800;
            color: var(--accent);
        }

        /* ─── Discount Badge ─── */
        .discount-badge {
            background: #f0fdf4;
            border: 1.5px solid #bbf7d0;
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            margin-bottom: 16px;
            font-size: 13px;
            color: #166534;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            direction: rtl;
        }

        .discount-badge strong {
            color: #15803d;
        }

        /* ─── Coupon ─── */
        .coupon-row {
            display: flex;
            gap: 10px;
        }

        .coupon-row .form-control-custom {
            flex: 1;
        }

        .btn-apply {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            padding: 0 22px;
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: background .2s;
        }

        .btn-apply:hover {
            background: var(--accent);
        }

        /* ─── Payment Accordion ─── */
        .pay-accordion-item {
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm) !important;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .accordion-button.pay-btn {
            background: var(--surface);
            color: var(--text-main);
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            border: none;
            box-shadow: none !important;
        }

        .accordion-button.pay-btn:not(.collapsed) {
            background: #fff8f3;
            color: var(--accent);
            border-bottom: 1.5px solid #fde8d4;
        }

        .accordion-button.pay-btn::after {
            margin-right: auto;
            margin-left: 0;
        }

        .pay-logo {
            height: 34px;
            width: auto;
            object-fit: contain;
            border-radius: 6px;
        }

        .accordion-body.pay-body {
            background: #fffaf6;
            padding: 18px 24px;
            font-size: 13px;
        }

        /* ─── Navigation Buttons ─── */
        .step-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 28px;
            direction: rtl;
        }

        .btn-next {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            padding: 13px 30px;
            font-family: 'Cairo', sans-serif;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(224, 123, 57, .3);
            transition: transform .15s, box-shadow .15s;
        }

        .btn-next:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(224, 123, 57, .4);
        }

        .btn-next:active {
            transform: translateY(0);
        }

        .btn-next:disabled {
            opacity: .55;
            cursor: not-allowed;
            transform: none;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            color: var(--text-muted);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 12px 22px;
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: border-color .2s, color .2s;
        }

        .btn-back:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-confirm {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            padding: 13px 30px;
            font-family: 'Cairo', sans-serif;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(224, 123, 57, .3);
            transition: transform .15s, box-shadow .15s;
        }

        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(224, 123, 57, .4);
            color: #fff;
        }

        .btn-confirm:active {
            transform: translateY(0);
        }

        /* ─── Contact Strip ─── */
        .contact-strip {
            background: var(--surface);
            border-radius: var(--radius);
            border: 1.5px solid var(--border);
            padding: 20px 24px;
            margin-top: 16px;
            text-align: center;
            box-shadow: var(--shadow);
        }

        .contact-strip p {
            color: var(--text-muted);
            font-size: 13px;
            margin-bottom: 14px;
        }

        .contact-btns {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-contact {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border-radius: var(--radius-sm);
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: transform .15s;
        }

        .btn-contact:hover {
            transform: translateY(-2px);
        }

        .btn-wa {
            background: #25d366;
            color: #fff;
        }

        .btn-fb {
            background: #1877f2;
            color: #fff;
        }

        /* ─── Responsive ─── */
        @media (max-width: 600px) {
            .checkout-wrapper {
                padding: 80px 12px 40px;
            }

            .step-card {
                padding: 22px 16px 18px;
            }

            .step-label {
                display: none;
            }

            .step-nav {
                flex-direction: column-reverse;
                gap: 10px;
            }

            .btn-next,
            .btn-back {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <!-- ═══════════════════════════════════════ -->
    <div class="checkout-wrapper">

        @if (session('success'))
            <div class="flash-alert flash-success">✅ {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="flash-alert flash-error">❌ {{ session('error') }}</div>
        @endif

        @php
            $cartSubtotal = (float) str_replace(',', '', Cart::subtotal());
            $discountAmount = session()->has('applied_discount') ? session('applied_discount')['amount'] : 0;
            $preShippingTotal = max($cartSubtotal - $discountAmount, 0);
        @endphp

        <!-- Mini Order Bar -->
        <div class="order-mini-bar">
            <div>
                <div class="mini-label">إجمالي المنتجات</div>
                <div class="mini-val">{{ number_format($preShippingTotal, 2) }} جنيه</div>
            </div>
            <div style="text-align:left;">
                <div class="mini-label">عدد المنتجات</div>
                <div class="mini-val" style="font-size:15px;">{{ Cart::instance('shopping')->content()->sum('qty') }} قطعة
                </div>
            </div>
        </div>

        <!-- ─── Progress Stepper ─── -->
        <div class="stepper-bar">
            <div class="step-item active" id="si-1">
                <div class="step-circle">🛍</div>
                <div class="step-label">مراجعة الطلب</div>
            </div>
            <div class="step-item" id="si-2">
                <div class="step-circle">📍</div>
                <div class="step-label">بيانات الشحن</div>
            </div>
            <div class="step-item" id="si-3">
                <div class="step-circle">🚚</div>
                <div class="step-label">طريقة التوصيل</div>
            </div>
            <div class="step-item" id="si-4">
                <div class="step-circle">💳</div>
                <div class="step-label">الدفع</div>
            </div>
        </div>

        <!-- Hidden Forms (always in DOM for JS serialization) -->
        <form id="order" style="display:none;">
            @foreach (Cart::instance('shopping')->content() as $item)
                <input type="hidden" name="product_id[]" value="{{ $item->id }}">
                <input type="hidden" name="amount[]" value="{{ $item->qty }}">
                <input type="hidden" name="price[]" value="{{ $item->price }}">
                <input type="hidden" name="size[]" value="{{ $item->options->size }}">
                <input type="hidden" name="color[]" value="{{ $item->options->color }}">
                <input type="hidden" name="total_price[]" value="{{ $item->subtotal() }}">
            @endforeach
        </form>
        <input type="hidden" name="all_total" value="{{ $preShippingTotal }}" id="all_total">
        <p id="total" style="display:none;">{{ $preShippingTotal }}</p>

        <!-- ══════════ STEP 1: Review ══════════ -->
        <div class="step-card" id="step-1">
            <div class="step-card-header">
                <div class="icon-circle">🛍</div>
                <div>
                    <h2>مراجعة طلبك</h2>
                    <p>تأكد من المنتجات قبل المتابعة</p>
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>السعر</th>
                            <th>الكمية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (Cart::instance('shopping')->content() as $item)
                            <tr>
                                <td><a href="{{ route('user.product.show', $item->id) }}"
                                        class="product-name-link">{{ $item->name }}</a></td>
                                <td><span class="price-tag">{{ number_format($item->price, 2) }} جنيه</span></td>
                                <td><span class="qty-badge">× {{ $item->qty }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Coupon -->
            @if ($discountSetting && $discountSetting->discount_enabled)
                <div style="margin-top:20px; padding-top:20px; border-top:1.5px solid var(--border);">
                    <label class="form-label-custom">🏷 هل معاك كوبون خصم؟</label>
                    <form action="{{ route('user.checkout.applyDiscount') }}" method="POST">
                        @csrf
                        <div class="coupon-row">
                            <input type="text" name="coupon_code" class="form-control-custom"
                                placeholder="ادخل كود الخصم"
                                @if (session()->has('applied_discount')) value="{{ session('applied_discount')['code'] }}" @endif>
                            <button type="submit" class="btn-apply">تطبيق</button>
                        </div>
                    </form>
                </div>
            @endif

            @if ($discountAmount > 0)
                <div class="discount-badge" style="margin-top:12px;">
                    🎉 تم تطبيق الخصم! الكوبون: <strong>{{ session('applied_discount')['code'] }}</strong> — وفرت
                    <strong>{{ $discountAmount }} جنيه</strong>
                </div>
            @endif

            <div class="step-nav">
                <button class="btn-next" onclick="goTo(2)">
                    التالي — بيانات الشحن
                    <span>←</span>
                </button>
                <div></div>
            </div>
        </div>

        <!-- ══════════ STEP 2: Location ══════════ -->
        <div class="step-card hidden" id="step-2">
            <div class="step-card-header">
                <div class="icon-circle">📍</div>
                <div>
                    <h2>بيانات الشحن</h2>
                    <p>سنوصل طلبك على هذا العنوان</p>
                </div>
            </div>

            <form id="location-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom" for="governorates">المحافظة</label>
                            <select class="form-control-custom" id="governorates" name="government"
                                onchange="calculateTotal()">
                                <option value="">اختر المحافظة</option>
                                @foreach ($governoratesData as $governorate)
                                    <option value="{{ $governorate->id }}" gov-price="{{ $governorate->price }}">
                                        {{ $governorate->governorate_name_ar }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom" for="cities">المدينة</label>
                            <select class="form-control-custom" id="cities" name="city" disabled>
                                <option value="">اختر المدينة</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group-custom">
                            <label class="form-label-custom" for="address">العنوان التفصيلي</label>
                            <input class="form-control-custom" id="address" name="address"
                                placeholder="الشارع، الحي، المبنى..." />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom" for="user_name">الاسم ثلاثي</label>
                            <input class="form-control-custom" id="user_name" name="user_name" placeholder="اسم المستلم"
                                required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom" for="mobile">رقم الموبايل</label>
                            <input class="form-control-custom" type="number" id="mobile" name="mobile"
                                pattern="\d{11}" minlength="11" maxlength="11" placeholder="01xxxxxxxxx" required />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group-custom">
                            <label class="form-label-custom" for="temp_mobile">رقم احتياطي</label>
                            <input class="form-control-custom" type="number" id="temp_mobile" name="temp_mobile"
                                pattern="\d{11}" minlength="11" maxlength="11" placeholder="01xxxxxxxxx" required />
                        </div>
                    </div>
                </div>
            </form>

            <div class="step-nav">
                <button class="btn-next" onclick="validateStep2()">
                    التالي — طريقة التوصيل
                    <span>←</span>
                </button>
                <button class="btn-back" onclick="goTo(1)">
                    <span>→</span> رجوع
                </button>
            </div>
        </div>

        <!-- ══════════ STEP 3: Shipping Method ══════════ -->
        <div class="step-card hidden" id="step-3">
            <div class="step-card-header">
                <div class="icon-circle">🚚</div>
                <div>
                    <h2>طريقة التوصيل</h2>
                    <p>اختار الطريقة الأنسبلك</p>
                </div>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom" for="shipping_method">اختر طريقة الشحن</label>
                <select class="form-control-custom" id="shipping_method" name="shipping_method_id">
                    <option value="">اختر طريقة الشحن</option>
                </select>
            </div>

            <div id="shipping_info" class="ship-info-box" style="display:none;">
                <div class="info-row" id="fee_row">
                    <span class="info-label">رسوم الخدمة</span>
                    <span class="info-value">+ <span id="shipping_fee">0.00</span> جنيه</span>
                </div>
                <div class="info-row" id="home_cost_row" style="display:none;">
                    <span class="info-label">تكلفة التوصيل للمنزل</span>
                    <span class="info-value"><span id="home_shipping_cost">0.00</span> جنيه</span>
                </div>
                <div class="info-row" id="post_cost_row" style="display:none;">
                    <span class="info-label">تكلفة التوصيل لمكتب البريد</span>
                    <span class="info-value">
                        <span id="post_shipping_cost">0.00</span> جنيه &nbsp;
                        <a href="{{ route('user.fqa') }}"
                            style="color:var(--accent); font-size:12px; font-weight:700;">تعرف على الاستلام ←</a>
                    </span>
                </div>
                <div class="info-row" id="address_row" style="display:none;">
                    <span class="info-label">العنوان</span>
                    <span class="info-value" id="shipping_address">---</span>
                </div>
                <div class="info-row" id="phones_row" style="display:none;">
                    <span class="info-label">أرقام الهاتف</span>
                    <span class="info-value" id="shipping_phones">---</span>
                </div>
            </div>

            <div id="nearpost_wrapper" style="display:none; margin-top:14px;">
                <div class="form-group-custom">
                    <label class="form-label-custom" for="near_post">اسم أقرب مكتب بريد</label>
                    <input type="text" id="near_post" name="near_post" class="form-control-custom"
                        placeholder="ادخل اسم مكتب البريد" />
                </div>
            </div>

            <!-- Live Total Preview -->
            <div
                style="background:linear-gradient(135deg,var(--primary) 0%,#2d2d50 100%); border-radius:var(--radius-sm); padding:16px 20px; margin-top:20px; direction:rtl; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <div style="color:rgba(255,255,255,.6); font-size:12px; font-weight:600;">الإجمالي مع الشحن</div>
                    <div id="all" style="color:var(--accent-light); font-size:22px; font-weight:800;">—</div>
                </div>
                <div style="text-align:left;">
                    <div style="color:rgba(255,255,255,.6); font-size:12px; font-weight:600;">مصاريف الشحن</div>
                    <div id="delivery" style="color:#fff; font-size:15px; font-weight:700;">—</div>
                </div>
            </div>

            <div class="step-nav">
                <button class="btn-next" onclick="validateStep3()">
                    التالي — الدفع
                    <span>←</span>
                </button>
                <button class="btn-back" onclick="goTo(2)">
                    <span>→</span> رجوع
                </button>
            </div>
        </div>

        <!-- ══════════ STEP 4: Payment ══════════ -->
        <div class="step-card hidden" id="step-4">
            <div class="step-card-header">
                <div class="icon-circle">💳</div>
                <div>
                    <h2>الدفع</h2>
                    <p>اختار طريقة الدفع وأكمل طلبك</p>
                </div>
            </div>

            <!-- Final Summary -->
            <div class="summary-lines" style="margin-bottom:24px;">
                <div class="sum-row">
                    <span class="s-lbl">إجمالي المنتجات</span>
                    <span class="s-val">{{ number_format($cartSubtotal, 2) }} جنيه</span>
                </div>
                @if ($discountAmount > 0)
                    <div class="sum-row">
                        <span class="s-lbl">الخصم</span>
                        <span class="s-val" style="color:var(--success);">- {{ $discountAmount }} جنيه</span>
                    </div>
                @endif
                <div class="sum-row">
                    <span class="s-lbl">مصاريف الشحن</span>
                    <span class="s-val" id="delivery-final">—</span>
                </div>
                <div class="sum-row total">
                    <span class="s-lbl">الإجمالي النهائي</span>
                    <span class="s-val" id="all-final">—</span>
                </div>
            </div>

            <!-- Payment Methods Accordion -->
            <div class="accordion" id="accordionExample">
                <div class="pay-accordion-item accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button pay-btn collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <img src="https://wp.logos-download.com/wp-content/uploads/2023/02/Fawry_Logo-3000x849.png"
                                class="pay-logo">
                            Fawry Pay
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body pay-body">
                            <p style="color:var(--text-muted); margin-bottom:14px;">يتم إضافة رسوم 1% + 2.5 جنيه للدفع
                                بفوري باي</p>
                            <button type="button" class="btn-confirm" id="fawry">✅ إكمال الدفع بفوري</button>
                        </div>
                    </div>
                </div>
                {{-- More payment items ... --}}
            </div>

            <div class="step-nav" style="margin-top:20px;">
                <div></div>
                <button class="btn-back" onclick="goTo(3)">
                    <span>→</span> رجوع
                </button>
            </div>
        </div>

        <!-- Contact -->
        <div class="contact-strip" style="margin-top:20px;">
            <p>لو قابلتك أي مشكلة تواصل معنا</p>
            <div class="contact-btns">
                <a href="https://wa.me/+201550234324" target="_blank" class="btn-contact btn-wa">📲 واتساب</a>
                <a href="https://www.facebook.com/highacademy2?mibextid=ZbWKwL" target="_blank"
                    class="btn-contact btn-fb">📘 فيس بوك</a>
            </div>
        </div>

    </div>
    <!-- ═══════════════════════════════════════ -->
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>

    @php
        $cartItems = Cart::instance('shopping')->content();
        $totalProducts = $cartItems->count();
        $totalQuantity = $cartItems->sum('qty');
        $productTax = 0;
        $productSlowTax = 0;
        if ($totalProducts > 1) {
            foreach ($cartItems as $product) {
                $productTax += $product->qty * $product->model->tax;
                $productSlowTax += $product->qty * $product->model->slowTax;
            }
        } elseif ($totalProducts == 1 && $cartItems->first()->qty > 1) {
            $product = $cartItems->first();
            $taxableQuantity = $product->qty - 1;
            $productTax += $taxableQuantity * $product->model->tax;
            $productSlowTax += $taxableQuantity * $product->model->slowTax;
        }
    @endphp

    <script>
        const TAX_HOME = {{ $productTax }};
        const TAX_POST = {{ $productSlowTax }};
        const TOTAL_QUANTITY = {{ $totalQuantity }};
        const shippingMethods = @json($shippingMethods);
        const governoratesData = @json($governoratesData);
        const citiesData = @json($citiesData);

        let currentStep = 1;

        /* ─── Stepper Navigation ─── */
        function goTo(n) {
            document.getElementById('step-' + currentStep).classList.add('hidden');
            currentStep = n;
            document.getElementById('step-' + n).classList.remove('hidden');
            document.getElementById('step-' + n).style.animation = 'none';
            requestAnimationFrame(() => {
                document.getElementById('step-' + n).style.animation = '';
            });

            // Update stepper UI
            for (let i = 1; i <= 4; i++) {
                const si = document.getElementById('si-' + i);
                si.classList.remove('active', 'done');
                if (i < n) si.classList.add('done');
                if (i === n) si.classList.add('active');
            }

            // Sync final summary on step 4
            if (n === 4) syncFinalSummary();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function validateStep2() {
            const gov = document.getElementById('governorates').value;
            const city = document.getElementById('cities').value;
            const addr = document.getElementById('address').value.trim();
            const name = document.getElementById('user_name').value.trim();
            const mob = document.getElementById('mobile').value.trim();
            const tmp = document.getElementById('temp_mobile').value.trim();
            if (!gov || !city || !addr || !name || !mob || !tmp) {
                Swal.fire({
                    icon: 'warning',
                    title: 'بيانات ناقصة',
                    text: 'من فضلك أكمل جميع البيانات قبل المتابعة',
                    confirmButtonColor: '#e07b39'
                });
                return;
            }
            goTo(3);
        }

        function validateStep3() {
            const method = document.getElementById('shipping_method').value;
            if (!method) {
                Swal.fire({
                    icon: 'warning',
                    title: 'اختار طريقة الشحن',
                    text: 'يرجى اختيار طريقة شحن قبل المتابعة',
                    confirmButtonColor: '#e07b39'
                });
                return;
            }
            const m = shippingMethods.find(x => x.id == method);
            const nearPost = document.getElementById('near_post').value.trim();
            if (m?.type === 'post' && !nearPost) {
                Swal.fire({
                    icon: 'warning',
                    title: 'بيانات ناقصة',
                    text: 'يرجى إدخال اسم أقرب مكتب بريد',
                    confirmButtonColor: '#e07b39'
                });
                return;
            }
            goTo(4);
        }

        function syncFinalSummary() {
            const d = document.getElementById('delivery');
            const a = document.getElementById('all');
            document.getElementById('delivery-final').innerText = d ? d.innerText : '—';
            document.getElementById('all-final').innerText = a ? a.innerText : '—';
        }

        /* ─── Shipping Options ─── */
        document.addEventListener('DOMContentLoaded', function() {
            const governoratesSelect = document.getElementById('governorates');
            const shippingSelect = document.getElementById('shipping_method');

            function updateShippingOptions() {
                const prev = shippingSelect.value;
                shippingSelect.innerHTML = '<option value="">اختر طريقة الشحن</option>';
                shippingMethods.forEach(m => {
                    const isPickup = m.type === 'branch';
                    if (!isPickup || (governoratesSelect.value && m.government == governoratesSelect
                        .value)) {
                        const opt = document.createElement('option');
                        opt.value = m.id;
                        opt.textContent = {
                            branch: 'استلام من المكتبة',
                            home: 'شحن لباب البيت',
                            post: 'شحن لمكتب بريد'
                        } [m.type] + ' — ' + m.name;
                        shippingSelect.appendChild(opt);
                    }
                });
                if (shippingSelect.options.length === 1) {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.disabled = true;
                    opt.textContent = shippingMethods.length === 0 ? 'لا توجد طريقة شحن للمنتجات الحالية' :
                        (governoratesSelect.value ? 'لا توجد طريقة شحن لهذه المحافظة' : 'اختر المحافظة أولاً');
                    shippingSelect.appendChild(opt);
                }
                if ([...shippingSelect.options].some(o => o.value === prev)) shippingSelect.value = prev;
                calculateTotal();
                updateShippingInfo();
            }

            function updateShippingInfo() {
                const sel = shippingSelect.value;
                const m = shippingMethods.find(x => x.id == sel);
                const info = document.getElementById('shipping_info');
                const wrapper = document.getElementById('nearpost_wrapper');
                const npInput = document.getElementById('near_post');
                const fee_row = document.getElementById('fee_row');
                const homeRow = document.getElementById('home_cost_row');
                const postRow = document.getElementById('post_cost_row');
                const homeVal = document.getElementById('home_shipping_cost');
                const postVal = document.getElementById('post_shipping_cost');

                if (!m) {
                    wrapper.style.display = 'none';
                    info.style.display = 'none';
                    if (homeRow) homeRow.style.display = 'none';
                    if (postRow) postRow.style.display = 'none';
                    return;
                }
                if (m.type === 'post') {
                    wrapper.style.display = 'block';
                    npInput.required = true;
                } else {
                    wrapper.style.display = 'none';
                    npInput.required = false;
                    npInput.value = '';
                }

                document.getElementById('shipping_fee').innerText = m.type === 'branch' ?
                    (Number(m.fee) * TOTAL_QUANTITY).toFixed(2) :
                    Number(m.fee).toFixed(2);

                const govId = document.getElementById('governorates').value;
                const matchedGov = governoratesData.find(g => g.id == govId);
                const rawHome = matchedGov ? (matchedGov.home_shipping_price ?? matchedGov.price) : null;
                const rawPost = matchedGov ? (matchedGov.post_shipping_price ?? matchedGov.price) : null;
                const homeBase = rawHome !== null && rawHome !== undefined ? Number(rawHome) : NaN;
                const postBase = rawPost !== null && rawPost !== undefined ? Number(rawPost) : NaN;
                const baseFee = Number(m.fee ?? 0);

                if (homeRow && postRow) {
                    homeRow.style.display = 'none';
                    postRow.style.display = 'none';
                    if (m.type === 'home' && govId) {
                        homeRow.style.display = 'flex';
                        homeVal.innerText = (baseFee + homeBase + TAX_HOME).toFixed(2);
                        if (Number.isFinite(postBase)) {
                            postRow.style.display = 'flex';
                            postVal.innerText = (baseFee + postBase + TAX_POST).toFixed(2);
                        }
                    } else if (m.type === 'post' && govId) {
                        postRow.style.display = 'flex';
                        postVal.innerText = (baseFee + postBase + TAX_POST).toFixed(2);
                        if (Number.isFinite(homeBase)) {
                            homeRow.style.display = 'flex';
                            homeVal.innerText = (baseFee + homeBase + TAX_HOME).toFixed(2);
                        }
                    }
                }
                const addr_r = document.getElementById('address_row');
                const ph_r = document.getElementById('phones_row');
                if (m.type === 'branch') {
                    fee_row.style.display = 'flex';
                    addr_r.style.display = 'flex';
                    ph_r.style.display = 'flex';
                    document.getElementById('shipping_address').innerText = m.address;
                    document.getElementById('shipping_phones').innerText = (m.phones || []).join(', ');
                } else {
                    fee_row.style.display = 'none';
                    addr_r.style.display = 'none';
                    ph_r.style.display = 'none';
                }
                info.style.display = 'block';
                calculateTotal();
            }

            governoratesSelect.addEventListener('change', updateShippingOptions);
            shippingSelect.addEventListener('change', updateShippingInfo);
            updateShippingOptions();

            // Cities
            governoratesSelect.addEventListener('change', function() {
                const govId = this.value;
                const citiesSelect = document.getElementById('cities');
                citiesSelect.innerHTML = '<option value="">اختر المدينة</option>';
                if (govId) {
                    citiesSelect.disabled = false;
                    citiesData.filter(c => c.governorate_id == govId).forEach(city => {
                        const o = document.createElement('option');
                        o.value = city.id;
                        o.textContent = city.name_ar;
                        citiesSelect.appendChild(o);
                    });
                } else {
                    citiesSelect.disabled = true;
                }
                calculateTotal();
            });
        });

        /* ─── Calculate Total ─── */
        function calculateTotal() {
            const sub = parseFloat(document.getElementById("total").innerText.trim()) || 0;
            const methId = parseInt(document.getElementById("shipping_method").value);
            const govId = parseInt(document.getElementById("governorates").value);
            const method = shippingMethods.find(m => m.id === methId);
            if (!method) {
                updateCostRows(null, 0);
                return;
            }

            let fee = Number(method.fee ?? 0);
            const gov = governoratesData.find(g => g.id == govId);
            const rawH = gov ? (gov.home_shipping_price ?? gov.price) : null;
            const rawP = gov ? (gov.post_shipping_price ?? gov.price) : null;
            const homeBase = rawH !== null && rawH !== undefined ? Number(rawH) : NaN;
            const postBase = rawP !== null && rawP !== undefined ? Number(rawP) : NaN;
            let tax = 0;

            if (method.type === 'home' && Number.isNaN(homeBase)) {
                updateCostRows(null, 0);
                return;
            }
            if (method.type === 'post' && Number.isNaN(postBase)) {
                updateCostRows(null, 0);
                return;
            }

            if (method.type === 'home') {
                tax = TAX_HOME;
                fee += (Number.isNaN(homeBase) ? 0 : homeBase) + tax;
                updateCostRows('home', fee);
            } else if (method.type === 'post') {
                tax = TAX_POST;
                fee += (Number.isNaN(postBase) ? 0 : postBase) + tax;
                updateCostRows('post', fee);
            } else {
                fee = fee * TOTAL_QUANTITY;
                updateCostRows('branch', fee);
            }

            const grand = sub + fee;
            document.querySelectorAll("#delivery").forEach(el => el.innerText = `جنيه ${fee.toFixed(2)}`);
            document.querySelectorAll("#all").forEach(el => el.innerText = `جنيه ${grand.toFixed(2)}`);
        }

        function updateCostRows(type, cost) {
            const hr = document.getElementById('home_cost_row');
            const pr = document.getElementById('post_cost_row');
            if (!hr || !pr) return;
            hr.style.display = 'none';
            pr.style.display = 'none';
            if (!type || !Number.isFinite(cost)) return;
            if (type === 'home') {
                hr.style.display = 'flex';
                document.getElementById('home_shipping_cost').innerText = cost.toFixed(2);
            } else if (type === 'post') {
                pr.style.display = 'flex';
                document.getElementById('post_shipping_cost').innerText = cost.toFixed(2);
            }
        }

        /* ─── Payment Handlers ─── */
        function setupPaymentHandler(buttonId, routeUrl, extraFormId = null) {
            $(buttonId).click(function() {
                const btn = $(this);
                const orig = btn.text();
                btn.prop('disabled', true).text('جاري المعالجة...');
                const selM = shippingMethods.find(m => m.id == document.getElementById('shipping_method').value);
                const np = document.getElementById('near_post');
                if (selM?.type === 'post' && !np.value.trim()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'يرجى إدخال اسم أقرب مكتب بريد'
                    });
                    btn.prop('disabled', false).text(orig);
                    return;
                }
                var fd = new FormData();
                $('#order').serializeArray().forEach(f => fd.append(f.name, f.value));
                fd.append('shipping_method', $('#shipping_method').val());
                $('#location-data').serializeArray().forEach(f => fd.append(f.name, f.value));
                fd.append('near_post', np.value.trim());
                if (extraFormId) {
                    $(extraFormId).serializeArray().forEach(f => fd.append(f.name, f.value));
                    var fi = $(extraFormId).find('input[type="file"]');
                    if (fi.length > 0 && fi[0].files.length > 0) fd.append(fi.attr('name'), fi[0].files[0]);
                }
                $.ajax({
                    url: routeUrl,
                    type: "POST",
                    data: fd,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(r) {
                        if (r.url) window.location.href = r.url;
                        else if (r.success) {
                            Swal.fire({
                                icon: 'success',
                                title: r.msg,
                                showConfirmButton: false
                            });
                            setTimeout(() => window.location.href = '/', 500);
                        } else {
                            btn.prop('disabled', false).text(orig);
                            Swal.fire({
                                icon: 'error',
                                title: r.msg || 'خطأ غير معروف',
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(x) {
                        btn.prop('disabled', false).text(orig);
                        Swal.fire({
                            icon: 'error',
                            title: x.responseJSON?.msg || 'خطا اثناء التنفيذ',
                            showConfirmButton: false
                        });
                    }
                });
            });
        }

        setupPaymentHandler('#credit_card', "{{ route('cards.pay') }}");
        setupPaymentHandler('#fawry', "{{ route('fawry.pay') }}");
        setupPaymentHandler('#wallet', "{{ route('fawry.wallet.pay') }}", '#ewallets-form');
        setupPaymentHandler('#insta-pay', "{{ route('manual.pay') }}", '#instapay-form');
    </script>

    <?php
    $addressId = null;
    $citiId = null;
    if ($orders !== null) {
        if (!empty($orders->governorate_id)) {
            $addressId = $orders->governorate_id;
        } else {
            foreach ($governoratesData as $g) {
                if ($orders->governorate == $g->governorate_name_ar) {
                    $addressId = $g->id;
                }
            }
        }
        foreach ($citiesData as $c) {
            if ($orders->city == $c->name_ar) {
                $citiId = $c->id;
            }
        }
    }
    ?>

    @if ($orders !== null)
        <script>
            Swal.fire({
                title: "سهلناها عليك 😊",
                text: "جبنا بيانات الشحن من طلبك السابق",
                icon: "success",
                confirmButtonText: "حسنًا",
                confirmButtonColor: "#e07b39",
                showCloseButton: true
            }).then(() => {
                document.getElementById('governorates').value = "{{ $addressId ?? '' }}";
                document.getElementById('governorates').dispatchEvent(new Event('change'));
                document.getElementById('cities').value = "{{ $citiId ?? '' }}";
                document.getElementById('cities').dispatchEvent(new Event('change'));
                document.getElementById('address').value = "{{ $orders->address }}";
                document.getElementById('user_name').value = "{{ $orders->name }}";
                document.getElementById('mobile').value = "{{ $orders->mobile }}";
                document.getElementById('temp_mobile').value = "{{ $orders->temp_mobile }}";
                document.getElementById('near_post').value = "{{ $orders->near_post }}";
            });
        </script>
    @endif

    <script>
        function showPreview(event) {
            if (event.target.files.length > 0) {
                var p = document.getElementById("file-ip-1-preview");
                p.src = URL.createObjectURL(event.target.files[0]);
                p.style.display = "block";
            }
        }

        function showPreview2(event) {
            if (event.target.files.length > 0) {
                var p = document.getElementById("file-ip-2-preview");
                p.src = URL.createObjectURL(event.target.files[0]);
                p.style.display = "block";
            }
        }
    </script>
@endsection
