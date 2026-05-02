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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        :root {
            --gold:       #C9922C;
            --gold-light: #E8B554;
            --gold-dim:   #7A5A1E;
            --ink:        #1A1209;
            --paper:      #FAF7F2;
            --cream:      #F2EDE4;
            --sand:       #E8DFCF;
            --muted:      #8A7B68;
            --success:    #2D6A4F;
            --danger:     #8B1A1A;
            --border:     #D9CFBE;
            --shadow:     rgba(26,18,9,0.10);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            background: var(--paper);
            font-family: 'Cairo', sans-serif;
            color: var(--ink);
            direction: rtl;
        }

        /* ── PAGE WRAPPER ── */
        .checkout-wrapper {
            min-height: 100vh;
            padding: 120px 0 80px;
            background:
                radial-gradient(ellipse 70% 40% at 10% 10%, rgba(201,146,44,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 50% 30% at 90% 80%, rgba(201,146,44,0.06) 0%, transparent 60%),
                var(--paper);
        }

        /* ── PAGE HEADER ── */
        .page-header {
            text-align: center;
            margin-bottom: 52px;
        }
        .page-header .eyebrow {
            font-family: 'Amiri', serif;
            font-size: 13px;
            letter-spacing: 3px;
            color: var(--gold);
            text-transform: uppercase;
            margin-bottom: 10px;
            display: block;
        }
        .page-header h1 {
            font-family: 'Amiri', serif;
            font-size: clamp(30px, 5vw, 48px);
            font-weight: 700;
            color: var(--ink);
            margin: 0 0 14px;
            line-height: 1.2;
        }
        .page-header .divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .page-header .divider::before,
        .page-header .divider::after {
            content: '';
            height: 1px;
            width: 80px;
            background: linear-gradient(90deg, transparent, var(--gold));
        }
        .page-header .divider::after {
            background: linear-gradient(90deg, var(--gold), transparent);
        }
        .page-header .divider span {
            color: var(--gold);
            font-size: 18px;
        }

        /* ── ALERTS ── */
        .alert-gold {
            background: linear-gradient(135deg, #FFF8EC, #FFF3DC);
            border: 1px solid var(--gold-light);
            border-right: 4px solid var(--gold);
            border-radius: 12px;
            color: var(--gold-dim);
            padding: 14px 20px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .alert-gold strong { color: var(--gold); }
        .alert-danger-custom {
            background: #FFF5F5;
            border: 1px solid #FBBFBF;
            border-right: 4px solid var(--danger);
            border-radius: 12px;
            color: var(--danger);
            padding: 14px 20px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* ── CARDS ── */
        .card-section {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 24px;
            box-shadow: 0 4px 24px var(--shadow);
            transition: box-shadow 0.3s ease;
        }
        .card-section:hover { box-shadow: 0 8px 40px rgba(26,18,9,0.13); }

        .card-header-custom {
            background: linear-gradient(135deg, var(--ink) 0%, #2D2010 100%);
            padding: 18px 28px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .card-header-custom .icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .card-header-custom h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
        }

        .card-body-custom { padding: 28px; }

        /* ── PRODUCTS TABLE ── */
        .products-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .products-table thead tr {
            background: var(--cream);
        }
        .products-table thead th {
            padding: 13px 16px;
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid var(--sand);
            white-space: nowrap;
        }
        .products-table tbody tr {
            transition: background 0.2s;
        }
        .products-table tbody tr:hover { background: var(--paper); }
        .products-table tbody td {
            padding: 14px 16px;
            font-size: 14px;
            border-bottom: 1px solid var(--sand);
            vertical-align: middle;
        }
        .products-table tbody tr:last-child td { border-bottom: none; }
        .product-link {
            color: var(--ink);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        .product-link:hover { color: var(--gold); }
        .price-badge {
            display: inline-block;
            background: linear-gradient(135deg, #FFF8EC, #FFF3DC);
            border: 1px solid var(--gold-light);
            color: var(--gold-dim);
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 13px;
        }
        .qty-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px; height: 32px;
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 50%;
            font-weight: 700;
            font-size: 13px;
            color: var(--ink);
        }

        /* ── FORM ELEMENTS ── */
        .form-label-custom {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .form-control-custom {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
            color: var(--ink);
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            appearance: none;
        }
        .form-control-custom:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,146,44,0.12);
        }
        .form-control-custom::placeholder { color: #C0B5A8; }
        select.form-control-custom {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238A7B68' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 14px center;
            padding-left: 36px;
            cursor: pointer;
        }

        .form-group-custom { margin-bottom: 20px; }

        /* ── COUPON STRIP ── */
        .coupon-strip {
            display: flex;
            gap: 10px;
            background: var(--cream);
            border: 1.5px dashed var(--border);
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 20px;
            align-items: center;
        }
        .coupon-strip input {
            flex: 1;
            border: none;
            background: transparent;
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
            color: var(--ink);
            outline: none;
            direction: rtl;
        }
        .coupon-strip input::placeholder { color: #C0B5A8; }
        .btn-coupon {
            background: var(--gold);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 9px 20px;
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-coupon:hover { background: var(--gold-dim); transform: scale(1.02); }

        /* ── SHIPPING METHOD CARD ── */
        .shipping-method-wrapper {
            background: linear-gradient(135deg, #FFF8EC 0%, #FFFDF8 100%);
            border: 1.5px solid var(--gold-light);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .shipping-method-wrapper .label {
            font-size: 13px;
            font-weight: 700;
            color: var(--gold-dim);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .shipping-method-wrapper .label::before {
            content: '🚚';
        }

        /* ── SHIPPING INFO BOX ── */
        #shipping_info {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            margin-top: 14px;
        }
        #shipping_info p {
            margin: 0 0 8px;
            font-size: 13px;
            color: var(--ink);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        #shipping_info p:last-child { margin-bottom: 0; }
        #shipping_info strong { color: var(--muted); min-width: 160px; display: inline-block; }

        /* ── NEAR POST ── */
        #nearpost_wrapper {
            background: #FFF8EC;
            border: 1.5px dashed var(--gold-light);
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 20px;
        }

        /* ── TOTALS BOX ── */
        .totals-box {
            background: linear-gradient(135deg, var(--ink) 0%, #2D2010 100%);
            border-radius: 16px;
            padding: 24px;
            color: #fff;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            font-size: 14px;
        }
        .totals-row:last-child { border-bottom: none; }
        .totals-row.grand {
            padding-top: 16px;
            margin-top: 4px;
        }
        .totals-row .label-t { color: rgba(255,255,255,0.65); }
        .totals-row .value-t { font-weight: 700; color: #fff; }
        .totals-row.grand .label-t { font-size: 16px; color: #fff; font-weight: 700; }
        .totals-row.grand .value-t {
            font-size: 22px;
            color: var(--gold-light);
            font-family: 'Amiri', serif;
        }

        /* ── PAYMENT ACCORDION ── */
        .hidden { display: none !important; }

        #accordionExample {
            margin-bottom: 24px;
        }
        #accordionExample .accordion-item {
            border: 1.5px solid var(--border);
            border-radius: 14px !important;
            overflow: hidden;
            margin-bottom: 10px;
            background: #fff;
        }
        #accordionExample .accordion-button {
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            font-size: 15px;
            background: #fff;
            color: var(--ink);
            border-radius: 0 !important;
            padding: 16px 20px;
            gap: 12px;
        }
        #accordionExample .accordion-button:not(.collapsed) {
            background: var(--cream);
            color: var(--gold-dim);
            box-shadow: none;
        }
        #accordionExample .accordion-button::after {
            filter: none;
        }
        #accordionExample .accordion-body {
            background: var(--paper);
            padding: 20px;
            text-align: center;
        }
        .btn-pay {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 13px 32px;
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(201,146,44,0.3);
        }
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(201,146,44,0.4);
        }
        .btn-pay:active { transform: scale(0.98); }

        /* ── SUPPORT SECTION ── */
        .support-section {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px;
            text-align: center;
            margin-top: 28px;
            box-shadow: 0 4px 24px var(--shadow);
        }
        .support-section p {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 18px;
            line-height: 1.8;
        }
        .support-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn-support {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 12px;
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: transform 0.15s, box-shadow 0.2s;
        }
        .btn-support:hover { transform: translateY(-2px); }
        .btn-whatsapp {
            background: linear-gradient(135deg, #25D366, #128C7E);
            color: #fff;
            box-shadow: 0 4px 14px rgba(37,211,102,0.3);
        }
        .btn-facebook {
            background: linear-gradient(135deg, #1877F2, #0D5CC7);
            color: #fff;
            box-shadow: 0 4px 14px rgba(24,119,242,0.3);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .checkout-wrapper { padding: 90px 0 60px; }
            .card-body-custom { padding: 18px; }
            .products-table thead th,
            .products-table tbody td { padding: 10px 10px; font-size: 13px; }
            .totals-box { padding: 18px; }
        }
    </style>

    <div class="checkout-wrapper">
        <div class="container">

            {{-- ── PAGE HEADER ── --}}
            <div class="page-header">
                <span class="eyebrow">متجرنا</span>
                <h1>تفاصيل الدفع</h1>
                <div class="divider"><span>✦</span></div>
            </div>

            {{-- ── FLASH MESSAGES ── --}}
            @if (session('success'))
                <div class="alert-gold">✓ &nbsp;{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert-danger-custom">⚠ &nbsp;{{ session('error') }}</div>
            @endif

            <div class="row g-4">

                {{-- ══════════ LEFT COLUMN: Products ══════════ --}}
                <div class="col-lg-6">
                    <div class="card-section">
                        <div class="card-header-custom">
                            <div class="icon">🛍</div>
                            <h3>المنتجات في سلتك</h3>
                        </div>
                        <div class="card-body-custom" style="padding: 0;">
                            <table class="products-table">
                                <thead>
                                    <tr>
                                        <th>وصف المنتج</th>
                                        <th>السعر</th>
                                        <th>الكمية</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <form id="order">
                                        @foreach (Cart::instance('shopping')->content() as $item)
                                            <tr>
                                                <input type="hidden" name="product_id[]" value="{{ $item->id }}">
                                                <input type="hidden" name="amount[]"     value="{{ $item->qty }}">
                                                <input type="hidden" name="price[]"      value="{{ $item->price }}">
                                                <input type="hidden" name="size[]"       value="{{ $item->options->size }}">
                                                <input type="hidden" name="color[]"      value="{{ $item->options->color }}">
                                                <input type="hidden" name="total_price[]" value="{{ $item->subtotal() }}">
                                                <td>
                                                    <a href="{{ route('user.product.show', $item->id) }}" class="product-link">
                                                        {{ $item->name }}
                                                    </a>
                                                </td>
                                                <td><span class="price-badge">{{ number_format($item->price, 2) }} ج</span></td>
                                                <td><span class="qty-badge">{{ $item->qty }}</span></td>
                                            </tr>
                                        @endforeach
                                    </form>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ══════════ RIGHT COLUMN: Location + Payment ══════════ --}}
                <div class="col-lg-6">

                    @php
                        $cartSubtotal   = (float) str_replace(',', '', Cart::subtotal());
                        $discountAmount = session()->has('applied_discount') ? session('applied_discount')['amount'] : 0;
                        $preShippingTotal = max($cartSubtotal - $discountAmount, 0);
                    @endphp

                    <input type="hidden" name="all_total" value="{{ $preShippingTotal }}" id="all_total">

                    {{-- ── DISCOUNT APPLIED BANNER ── --}}
                    @if ($discountAmount > 0)
                        <div class="alert-gold">
                            🎉 تم تطبيق الخصم بنجاح!<br>
                            الكوبون: <strong>{{ session('applied_discount')['code'] }}</strong> &nbsp;|&nbsp;
                            الخصم: <strong>{{ $discountAmount }} جنيه</strong>
                        </div>
                    @endif

                    {{-- ── LOCATION CARD ── --}}
                    <div class="card-section">
                        <div class="card-header-custom">
                            <div class="icon">📍</div>
                            <h3>بيانات التوصيل</h3>
                        </div>
                        <div class="card-body-custom">

                            <form id="location-data">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">المحافظة</label>
                                            <select class="form-control-custom" id="governorates" name="government" onchange="calculateTotal()">
                                                <option value="">اختر المحافظة</option>
                                                @foreach ($governoratesData as $governorate)
                                                    <option value="{{ $governorate->id }}" gov-price="{{ $governorate->price }}">
                                                        {{ $governorate->governorate_name_ar }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">المدينة</label>
                                            <select class="form-control-custom" id="cities" name="city" disabled>
                                                <option value="">اختر المدينة</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group-custom">
                                    <label class="form-label-custom">العنوان التفصيلي</label>
                                    <input class="form-control-custom" id="address" name="address" placeholder="مثال: شارع الجمهورية، أمام المسجد، الدور الثاني" />
                                </div>

                                <div class="form-group-custom">
                                    <label class="form-label-custom">الاسم ثلاثي (كما في البطاقة)</label>
                                    <input class="form-control-custom" id="user_name" name="user_name" placeholder="اسم المستلم" required />
                                </div>

                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">رقم الموبايل</label>
                                            <input class="form-control-custom" type="number" id="mobile" name="mobile"
                                                pattern="\d{11}" minlength="11" maxlength="11" placeholder="01xxxxxxxxx" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">رقم احتياطي</label>
                                            <input class="form-control-custom" type="number" id="temp_mobile" name="temp_mobile"
                                                pattern="\d{11}" minlength="11" maxlength="11" placeholder="01xxxxxxxxx" required />
                                        </div>
                                    </div>
                                </div>

                            </form>

                            {{-- ── COUPON ── --}}
                            @if ($discountSetting && $discountSetting->discount_enabled)
                                <form action="{{ route('user.checkout.applyDiscount') }}" method="POST">
                                    @csrf
                                    <div class="coupon-strip">
                                        <span style="font-size:18px;">🏷</span>
                                        <input type="text" name="coupon_code" placeholder="ادخل كود الخصم"
                                            @if(session()->has('applied_discount')) value="{{ session('applied_discount')['code'] }}" @endif>
                                        <button type="submit" class="btn-coupon">تطبيق</button>
                                    </div>
                                </form>
                            @endif

                            {{-- ── SHIPPING METHOD ── --}}
                            <div class="shipping-method-wrapper">
                                <div class="label">اختر طريقة الاستلام</div>
                                <select class="form-control-custom" id="shipping_method" name="shipping_method_id">
                                    <option value="">اختر طريقة الشحن</option>
                                </select>

                                <div id="shipping_info" style="display: none;">
                                    <p id="fee_row"><strong>رسوم الخدمة:</strong> <span id="shipping_fee">0.00</span> جنيه</p>
                                    <p id="home_cost_row" style="display: none;">
                                        <strong>تكلفة التوصيل للمنزل:</strong>
                                        <span id="home_shipping_cost">0.00</span> جنيه
                                    </p>
                                    <p id="post_cost_row" style="display: none;">
                                        <strong>تكلفة التوصيل للبريد:</strong>
                                        <span id="post_shipping_cost">0.00</span> جنيه &nbsp;
                                        <a href="{{ route('user.fqa') }}" class="btn-pay" style="font-size:12px;padding:6px 14px;">تعرف على طريقة الاستلام</a>
                                    </p>
                                    <p id="address_row"><strong>العنوان:</strong> <span id="shipping_address">---</span></p>
                                    <p id="phones_row"><strong>أرقام الهاتف:</strong> <span id="shipping_phones">---</span></p>
                                </div>
                            </div>

                            {{-- ── NEAR POST ── --}}
                            <div id="nearpost_wrapper" style="display: none;">
                                <label class="form-label-custom">اسم أقرب مكتب بريد</label>
                                <input type="text" id="near_post" name="near_post" class="form-control-custom"
                                    placeholder="ادخل اسم مكتب البريد" />
                            </div>

                            {{-- ── TOTALS ── --}}
                            <div class="totals-box mt-3">
                                <div class="totals-row">
                                    <span class="label-t">مصاريف الشحن</span>
                                    <span class="value-t" id="delivery">—</span>
                                </div>
                                <div class="totals-row grand">
                                    <span class="label-t">الإجمالي</span>
                                    <span class="value-t" id="all">—</span>
                                </div>
                            </div>

                        </div>{{-- /card-body --}}
                    </div>{{-- /card-section --}}

                    {{-- ── PAYMENT ACCORDION ── --}}
                    <div class="accordion hidden" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                    aria-expanded="false" aria-controls="collapseFour">
                                    <img src="https://logos-download.com/wp-content/uploads/2023/02/Fawry_Logo.png" height="36px">
                                    <span style="margin-right:10px;">Fawry Pay</span>
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p style="color: var(--muted); margin-bottom:16px;">يتم إضافة رسوم 1% + 2.5 جنيه للدفع بفوري باي</p>
                                    <button type="button" class="btn-pay" id="fawry">اضغط لإكمال عملية الدفع</button>
                                </div>
                            </div>
                        </div>
                        {{-- More payment items here as needed --}}
                    </div>

                </div>{{-- /right col --}}
            </div>{{-- /row --}}

            {{-- ── SUPPORT ── --}}
            <div class="support-section">
                <p>لو قابلتك أي مشكلة، تواصل معنا عن طريق الواتساب أو صفحتنا على فيسبوك وهنكون معاك على طول 💛</p>
                <div class="support-btns">
                    <a href="https://wa.me/+201060683708" target="_blank" class="btn-support btn-whatsapp">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        الواتساب
                    </a>
                    <a href="https://www.facebook.com/highacademy2?mibextid=ZbWKwL" target="_blank" class="btn-support btn-facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        صفحة الفيس بوك
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- Hidden total for JS --}}
    <p id="total" style="display: none;">{{ $preShippingTotal }}</p>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>

    @php
        $cartItems     = Cart::instance('shopping')->content();
        $totalProducts = $cartItems->count();
        $totalQuantity = $cartItems->sum('qty');
        $productTax    = 0;
        $productSlowTax = 0;
        if ($totalProducts > 1) {
            foreach ($cartItems as $product) {
                $productTax     += $product->qty * $product->model->tax;
                $productSlowTax += $product->qty * $product->model->slowTax;
            }
        } elseif ($totalProducts == 1 && $cartItems->first()->qty > 1) {
            $product        = $cartItems->first();
            $taxableQuantity = $product->qty - 1;
            $productTax     += $taxableQuantity * $product->model->tax;
            $productSlowTax += $taxableQuantity * $product->model->slowTax;
        }
    @endphp

    <script>
        const TAX_HOME      = {{ $productTax }};
        const TAX_POST      = {{ $productSlowTax }};
        const TOTAL_QUANTITY = {{ $totalQuantity }};
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const allShippingMethods  = @json($shippingMethods);
            const governoratesDataset = @json($governoratesData);
            const governoratesSelect  = document.getElementById('governorates');
            const shippingSelect      = document.getElementById('shipping_method');

            function updateShippingInfo() {
                const sel     = shippingSelect.value;
                const m       = allShippingMethods.find(x => x.id == sel);
                const info    = document.getElementById('shipping_info');
                const wrapper = document.getElementById('nearpost_wrapper');
                const npInput = document.getElementById('near_post');
                const fee_row = document.getElementById('fee_row');
                const homeRow = document.getElementById('home_cost_row');
                const postRow = document.getElementById('post_cost_row');
                const homeValue = document.getElementById('home_shipping_cost');
                const postValue = document.getElementById('post_shipping_cost');

                if (!m) {
                    wrapper.style.display = 'none';
                    info.style.display    = 'none';
                    if (homeRow) homeRow.style.display = 'none';
                    if (postRow) postRow.style.display = 'none';
                    return;
                }

                if (m.type === 'post') {
                    wrapper.style.display = 'block';
                    npInput.required      = true;
                } else {
                    wrapper.style.display = 'none';
                    npInput.required      = false;
                    npInput.value         = '';
                }

                if (m.type === 'branch') {
                    document.getElementById('shipping_fee').innerText = (Number(m.fee) * TOTAL_QUANTITY).toFixed(2);
                } else {
                    document.getElementById('shipping_fee').innerText = Number(m.fee).toFixed(2);
                }

                const govId      = governoratesSelect.value;
                const matchedGov = governoratesDataset.find(g => g.id == govId);
                const rawHomeBase = matchedGov ? (matchedGov.home_shipping_price ?? matchedGov.price) : null;
                const rawPostBase = matchedGov ? (matchedGov.post_shipping_price ?? matchedGov.price) : null;
                const homeBase   = rawHomeBase !== null && rawHomeBase !== undefined ? Number(rawHomeBase) : NaN;
                const postBase   = rawPostBase !== null && rawPostBase !== undefined ? Number(rawPostBase) : NaN;
                const baseFee    = Number(m.fee ?? 0);

                if (homeRow && postRow) {
                    homeRow.style.display = 'none';
                    postRow.style.display = 'none';

                    if (m.type === 'home' && govId) {
                        homeRow.style.display = 'block';
                        homeValue.innerText   = (baseFee + homeBase + TAX_HOME).toFixed(2);
                        if (Number.isFinite(postBase)) {
                            postRow.style.display = 'block';
                            postValue.innerText   = (baseFee + postBase + TAX_POST).toFixed(2);
                        } else {
                            postRow.style.display = 'none';
                        }
                    } else if (m.type === 'post' && govId) {
                        postRow.style.display = 'block';
                        postValue.innerText   = (baseFee + postBase + TAX_POST).toFixed(2);
                        if (Number.isFinite(homeBase)) {
                            homeRow.style.display = 'block';
                            homeValue.innerText   = (baseFee + homeBase + TAX_HOME).toFixed(2);
                        } else {
                            homeRow.style.display = 'none';
                        }
                    }
                }

                const addr  = document.getElementById('address_row');
                const phone = document.getElementById('phones_row');
                if (m.type === 'branch') {
                    fee_row.style.display = 'block';
                    addr.style.display    = 'block';
                    phone.style.display   = 'block';
                    document.getElementById('shipping_address').innerText = m.address;
                    document.getElementById('shipping_phones').innerText  = (m.phones || []).join(', ');
                } else {
                    fee_row.style.display = 'none';
                    addr.style.display    = 'none';
                    phone.style.display   = 'none';
                }

                info.style.display = 'block';
                calculateTotal();
            }

            function updateShippingOptions() {
                const prev = shippingSelect.value;
                shippingSelect.innerHTML = '<option value="">اختر طريقة الشحن</option>';
                allShippingMethods.forEach(m => {
                    const isPickup = m.type === 'branch';
                    if (!isPickup || (governoratesSelect.value && m.government == governoratesSelect.value)) {
                        const opt   = document.createElement('option');
                        opt.value   = m.id;
                        const label = { branch: 'استلام من المكتبة', home: 'شحن لباب البيت', post: 'شحن لمكتب بريد' }[m.type] + ' — ' + m.name;
                        opt.textContent = label;
                        shippingSelect.appendChild(opt);
                    }
                });
                if (shippingSelect.options.length === 1) {
                    const opt       = document.createElement('option');
                    opt.value       = '';
                    opt.disabled    = true;
                    opt.textContent = allShippingMethods.length === 0
                        ? 'لا توجد طريقة شحن متاحة للمنتجات الحالية'
                        : (governoratesSelect.value
                            ? 'لا توجد طريقة شحن متاحة لهذه المحافظة'
                            : 'اختر المحافظة لعرض طرق الشحن المتاحة');
                    shippingSelect.appendChild(opt);
                }
                if ([...shippingSelect.options].some(o => o.value === prev)) shippingSelect.value = prev;
                calculateTotal();
                updateShippingInfo();
            }

            governoratesSelect.addEventListener('change', updateShippingOptions);
            shippingSelect.addEventListener('change', updateShippingInfo);
            updateShippingOptions();
        });
    </script>

    <script>
        const shippingMethods  = @json($shippingMethods);
        const governoratesData = @json($governoratesData);
        const citiesData       = @json($citiesData);
        const shippingSelect   = document.getElementById('shipping_method');

        function setupPaymentHandler(buttonId, routeUrl, extraFormId = null) {
            $(buttonId).click(function() {
                const btn          = $(this);
                const originalText = btn.text();
                btn.prop('disabled', true).text('جاري المعالجة...');

                const selectedMethod = shippingMethods.find(m => m.id == shippingSelect.value);
                const nearPostInput  = document.getElementById('near_post');

                if (selectedMethod?.type === 'post' && !nearPostInput.value.trim()) {
                    Swal.fire({ icon: 'error', title: 'خطأ', text: 'يرجى إدخال اسم أقرب مكتب بريد قبل المتابعة' });
                    btn.prop('disabled', false).text(originalText);
                    return;
                }

                var formData = new FormData();
                $('#order').serializeArray().forEach(function(field) { formData.append(field.name, field.value); });
                formData.append('shipping_method', $('#shipping_method').val());
                $('#location-data').serializeArray().forEach(function(field) { formData.append(field.name, field.value); });
                formData.append('near_post', nearPostInput.value.trim());

                if (extraFormId) {
                    $(extraFormId).serializeArray().forEach(function(field) { formData.append(field.name, field.value); });
                    var fileInput = $(extraFormId).find('input[type="file"]');
                    if (fileInput.length > 0 && fileInput[0].files.length > 0) {
                        formData.append(fileInput.attr('name'), fileInput[0].files[0]);
                    }
                }

                $.ajax({
                    url: routeUrl, type: "POST", data: formData, processData: false, contentType: false,
                    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    success: function(response) {
                        if (response.url) {
                            window.location.href = response.url;
                        } else if (response.success) {
                            Swal.fire({ icon: 'success', title: response.msg, showConfirmButton: false });
                            setTimeout(function() { window.location.href = '/'; }, 500);
                        } else {
                            btn.prop('disabled', false).text(originalText);
                            Swal.fire({ icon: 'error', title: response.msg || 'An unknown error occurred.', showConfirmButton: false });
                        }
                    },
                    error: function(jqXHR) {
                        btn.prop('disabled', false).text(originalText);
                        var errorMessage = jqXHR.responseJSON && jqXHR.responseJSON.msg ? jqXHR.responseJSON.msg : 'خطا اثناء التنفيذ';
                        Swal.fire({ icon: 'error', title: errorMessage, showConfirmButton: false });
                    }
                });
            });
        }

        setupPaymentHandler('#credit_card', "{{ route('cards.pay') }}");
        setupPaymentHandler('#fawry',        "{{ route('fawry.pay') }}");
        setupPaymentHandler('#wallet',       "{{ route('fawry.wallet.pay') }}", '#ewallets-form');
        setupPaymentHandler('#insta-pay',    "{{ route('manual.pay') }}",       '#instapay-form');

        function calculateTotal() {
            const discountedSubtotal = parseFloat(document.getElementById("total").innerText.trim()) || 0;
            const shippingMethodId   = parseInt(document.getElementById("shipping_method").value);
            const governorateId      = parseInt(document.getElementById("governorates").value);
            const method             = shippingMethods.find(m => m.id === shippingMethodId);
            let grandTotal           = discountedSubtotal;

            if (!method) { updateCostRows(null, 0); return; }

            let deliveryFee  = Number(method.fee ?? 0);
            const gov        = governoratesData.find(g => g.id == governorateId);
            const rawHomeBase = gov ? (gov.home_shipping_price ?? gov.price) : null;
            const rawPostBase = gov ? (gov.post_shipping_price ?? gov.price) : null;
            const homeBase   = rawHomeBase !== null && rawHomeBase !== undefined ? Number(rawHomeBase) : NaN;
            const postBase   = rawPostBase !== null && rawPostBase !== undefined ? Number(rawPostBase) : NaN;
            let appliedTax   = 0;

            if (method.type === 'home' && Number.isNaN(homeBase)) { updateCostRows(null, 0); return; }
            if (method.type === 'post' && Number.isNaN(postBase)) { updateCostRows(null, 0); return; }

            if (method.type === 'home') {
                appliedTax   = TAX_HOME;
                deliveryFee += (Number.isNaN(homeBase) ? 0 : homeBase) + appliedTax;
                updateCostRows('home', deliveryFee);
            } else if (method.type === 'post') {
                appliedTax   = TAX_POST;
                deliveryFee += (Number.isNaN(postBase) ? 0 : postBase) + appliedTax;
                updateCostRows('post', deliveryFee);
            } else {
                deliveryFee = deliveryFee * TOTAL_QUANTITY;
                updateCostRows('branch', deliveryFee);
            }

            grandTotal = discountedSubtotal + deliveryFee;

            document.querySelectorAll("#delivery").forEach(el => { el.innerText = `جنيه ${deliveryFee.toFixed(2)}`; });
            document.querySelectorAll("#all").forEach(el      => { el.innerText = `جنيه ${grandTotal.toFixed(2)}`; });
            const shippingTaxEl = document.getElementById('shippingTax');
            if (shippingTaxEl) shippingTaxEl.innerText = `جنيه ${appliedTax.toFixed(2)}`;
        }

        function updateCostRows(type, cost) {
            const homeRow   = document.getElementById('home_cost_row');
            const postRow   = document.getElementById('post_cost_row');
            const homeValue = document.getElementById('home_shipping_cost');
            const postValue = document.getElementById('post_shipping_cost');
            if (!homeRow || !postRow) return;
            homeRow.style.display = 'none';
            postRow.style.display = 'none';
            if (!type || !Number.isFinite(cost)) return;
            if (type === 'home') { homeRow.style.display = 'block'; homeValue.innerText = cost.toFixed(2); }
            else if (type === 'post') { postRow.style.display = 'block'; postValue.innerText = cost.toFixed(2); }
        }

        document.getElementById('shipping_method').addEventListener('change', function() { calculateTotal(); });
    </script>

    <?php
    $addressId = null;
    $citiId    = null;
    if ($orders !== null) {
        if (!empty($orders->governorate_id)) {
            $addressId = $orders->governorate_id;
        } else {
            foreach ($governoratesData as $governorate) {
                if ($orders->governorate == $governorate->governorate_name_ar) $addressId = $governorate->id;
            }
        }
        foreach ($citiesData as $cities) {
            if ($orders->city == $cities->name_ar) $citiId = $cities->id;
        }
    }
    ?>

    <script>
        var accordion = document.getElementById('accordionExample');
        accordion.classList.add('hidden');

        document.addEventListener('DOMContentLoaded', function() {
            const citiesData = @json($citiesData);

            document.getElementById('governorates').addEventListener('change', function() {
                const governorateId = this.value;
                const citiesSelect  = document.getElementById('cities');
                citiesSelect.innerHTML = '<option value="">اختر المدينة</option>';

                if (governorateId) {
                    citiesSelect.disabled = false;
                    const filteredCities  = citiesData.filter(city => city.governorate_id == governorateId);
                    filteredCities.forEach(city => {
                        const option       = document.createElement('option');
                        option.value       = city.id;
                        option.textContent = city.name_ar;
                        citiesSelect.appendChild(option);
                    });
                } else {
                    citiesSelect.disabled = true;
                }

                updateFormState();
                calculateTotal();
            });

            document.getElementById('cities').addEventListener('change', function() {
                updateFormState();
                calculateTotal();
            });

            function updateFormState() {
                const governorate      = document.getElementById('governorates').value;
                const city             = document.getElementById('cities').value;
                const shippingMethodId = document.getElementById('shipping_method').value;
                const selectedMethod   = shippingMethods.find(m => m.id == shippingMethodId);

                let show = false;
                if (selectedMethod) {
                    if (selectedMethod.type === 'branch') { show = true; }
                    else if (governorate && city) { show = true; }
                }

                if (show) { accordion.classList.remove('hidden'); }
                else       { accordion.classList.add('hidden'); }
            }

            calculateTotal();
            updateFormState();
            document.getElementById('shipping_method').addEventListener('change', updateFormState);
        });
    </script>

    @if ($orders !== null)
        <script>
            Swal.fire({
                title: "سهلناها عليك، جبنا بيانات الشحن من طلبك السابق",
                icon: "success",
                confirmButtonText: "حسناً",
                showCloseButton: true
            }).then((result) => {
                document.getElementById('governorates').value = "{{ $addressId ?? '' }}";
                document.getElementById('governorates').dispatchEvent(new Event('change'));
                document.getElementById('cities').value     = "{{ $citiId ?? '' }}";
                document.getElementById('cities').dispatchEvent(new Event('change'));
                document.getElementById('address').value    = "{{ $orders->address }}";
                document.getElementById('user_name').value  = "{{ $orders->name }}";
                document.getElementById('mobile').value     = "{{ $orders->mobile }}";
                document.getElementById('temp_mobile').value = "{{ $orders->temp_mobile }}";
                document.getElementById('near_post').value  = "{{ $orders->near_post }}";
            });
        </script>
    @endif

    <script type="text/javascript">
        function showPreview(event) {
            if (event.target.files.length > 0) {
                var src     = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("file-ip-1-preview");
                preview.src = src;
                preview.style.display = "block";
            }
        }
        function showPreview2(event) {
            if (event.target.files.length > 0) {
                var src     = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("file-ip-2-preview");
                preview.src = src;
                preview.style.display = "block";
            }
        }
    </script>
@endsection
