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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

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
    --radius: 16px;
    --radius-sm: 10px;
    --shadow: 0 4px 24px rgba(26,26,46,0.07);
    --shadow-sm: 0 2px 8px rgba(26,26,46,0.05);
  }

  * { box-sizing: border-box; }

  body {
    font-family: 'Cairo', sans-serif !important;
    background: #f0f0f5;
    color: var(--text-main);
  }

  .hidden { display: none !important; }

  /* ─── Page Wrapper ─── */
  .checkout-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 100px 20px 60px;
  }

  /* ─── Page Header ─── */
  .page-header {
    text-align: center;
    margin-bottom: 40px;
  }
  .page-header .badge-tag {
    display: inline-block;
    background: var(--accent);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    padding: 4px 14px;
    border-radius: 20px;
    margin-bottom: 10px;
    text-transform: uppercase;
  }
  .page-header h1 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary);
    margin: 0;
  }

  /* ─── Cards ─── */
  .card-block {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1.5px solid var(--border);
    box-shadow: var(--shadow);
    padding: 28px 28px;
    margin-bottom: 20px;
    transition: box-shadow .2s;
  }
  .card-block:hover { box-shadow: 0 8px 32px rgba(26,26,46,0.11); }

  .card-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .card-title .icon-circle {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    font-size: 14px;
    flex-shrink: 0;
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
    letter-spacing: .5px;
    text-transform: uppercase;
    padding: 12px 16px;
    border-bottom: 1.5px solid var(--border);
  }
  .product-table thead th:first-child { border-radius: 0 10px 0 0; }
  .product-table thead th:last-child { border-radius: 10px 0 0 0; }

  .product-table tbody tr {
    transition: background .15s;
  }
  .product-table tbody tr:hover { background: var(--surface-alt); }
  .product-table tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
    font-size: 14px;
  }
  .product-table tbody tr:last-child td { border-bottom: none; }

  .product-name-link {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
    transition: color .15s;
  }
  .product-name-link:hover { color: var(--accent); }

  .qty-badge {
    display: inline-block;
    background: var(--surface-alt);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 3px 12px;
    font-size: 13px;
    font-weight: 600;
  }

  .price-tag {
    font-weight: 700;
    color: var(--accent);
    font-size: 14px;
  }

  /* ─── Form Controls ─── */
  .form-label-custom {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 6px;
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
    transition: border-color .2s, box-shadow .2s;
    outline: none;
    appearance: none;
    -webkit-appearance: none;
  }
  .form-control-custom:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(224,123,57,.13);
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

  .form-group-custom { margin-bottom: 16px; }

  /* ─── Discount Alert ─── */
  .discount-badge {
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    border-radius: var(--radius-sm);
    padding: 14px 18px;
    margin-bottom: 16px;
    font-size: 13px;
    color: #166534;
    display: flex;
    align-items: flex-start;
    gap: 10px;
  }
  .discount-badge .disc-icon {
    font-size: 18px;
    flex-shrink: 0;
  }
  .discount-badge strong { color: #15803d; }

  /* ─── Coupon Row ─── */
  .coupon-row {
    display: flex;
    gap: 10px;
    margin-bottom: 4px;
  }
  .coupon-row .form-control-custom { flex: 1; }
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
    transition: background .2s, transform .1s;
  }
  .btn-apply:hover { background: var(--accent); transform: translateY(-1px); }
  .btn-apply:active { transform: translateY(0); }

  /* ─── Shipping Method Card ─── */
  .shipping-card {
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 18px 20px;
    background: var(--surface-alt);
    margin-bottom: 16px;
  }
  .shipping-card .ship-label {
    font-size: 13px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 10px;
  }

  /* ─── Shipping Info Details ─── */
  #shipping_info {
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid var(--border);
  }
  .info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    padding: 6px 0;
    border-bottom: 1px dashed #ebebf5;
    direction: rtl;
  }
  .info-row:last-child { border-bottom: none; }
  .info-row .info-label { color: var(--text-muted); font-weight: 600; }
  .info-row .info-value { font-weight: 700; color: var(--primary); }

  /* ─── Near Post Input ─── */
  #nearpost_wrapper {
    margin-top: 4px;
    margin-bottom: 16px;
  }

  /* ─── Order Summary Rows ─── */
  .summary-block {
    border-top: 1.5px solid var(--border);
    padding-top: 18px;
    margin-top: 4px;
  }
  .summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    direction: rtl;
    margin-bottom: 10px;
    font-size: 14px;
  }
  .summary-row .s-label { color: var(--text-muted); font-weight: 600; }
  .summary-row .s-value { font-weight: 700; color: var(--text-main); }
  .summary-row.total-row {
    margin-top: 10px;
    padding-top: 14px;
    border-top: 1.5px solid var(--border);
  }
  .summary-row.total-row .s-label { font-size: 17px; font-weight: 800; color: var(--primary); }
  .summary-row.total-row .s-value { font-size: 20px; font-weight: 800; color: var(--accent); }

  /* ─── Payment Accordion ─── */
  #accordionExample { margin-top: 20px; }

  .pay-accordion-item {
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm) !important;
    margin-bottom: 10px;
    overflow: hidden;
    transition: box-shadow .2s;
  }
  .pay-accordion-item:hover { box-shadow: var(--shadow-sm); }

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
    filter: none;
  }

  .pay-logo {
    height: 36px;
    width: auto;
    object-fit: contain;
    border-radius: 6px;
  }

  .accordion-body.pay-body {
    background: #fffaf6;
    padding: 18px 24px;
    font-size: 13px;
  }

  /* ─── CTA Buttons ─── */
  .btn-confirm {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    padding: 13px 28px;
    font-family: 'Cairo', sans-serif;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: transform .15s, box-shadow .15s;
    box-shadow: 0 4px 14px rgba(224,123,57,.3);
  }
  .btn-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(224,123,57,.4);
    color: #fff;
  }
  .btn-confirm:active { transform: translateY(0); }

  /* ─── Contact Strip ─── */
  .contact-strip {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1.5px solid var(--border);
    padding: 24px 28px;
    margin-top: 24px;
    text-align: center;
    box-shadow: var(--shadow-sm);
  }
  .contact-strip p {
    color: var(--text-muted);
    font-size: 14px;
    margin-bottom: 16px;
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
    padding: 11px 22px;
    border-radius: var(--radius-sm);
    font-family: 'Cairo', sans-serif;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    transition: transform .15s, box-shadow .15s;
  }
  .btn-contact:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.15); }
  .btn-wa { background: #25d366; color: #fff; }
  .btn-fb { background: #1877f2; color: #fff; }

  /* ─── Flash Alerts ─── */
  .flash-alert {
    border-radius: var(--radius-sm);
    padding: 12px 18px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .flash-success { background: #f0fdf4; border: 1.5px solid #bbf7d0; color: #166534; }
  .flash-error { background: #fef2f2; border: 1.5px solid #fecaca; color: #991b1b; }

  /* ─── Responsive ─── */
  @media (max-width: 768px) {
    .checkout-wrapper { padding: 80px 12px 40px; }
    .card-block { padding: 18px 14px; }
    .page-header h1 { font-size: 1.4rem; }
    .contact-btns { flex-direction: column; align-items: center; }
    .btn-contact { width: 100%; justify-content: center; }
  }

  /* ─── Animate in ─── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .card-block, .contact-strip {
    animation: fadeUp .45s ease both;
  }
  .card-block:nth-child(2) { animation-delay: .07s; }
  .card-block:nth-child(3) { animation-delay: .14s; }
  .card-block:nth-child(4) { animation-delay: .21s; }
</style>

<div class="checkout-wrapper">

  <!-- Page Header -->
  <div class="page-header">
    <span class="badge-tag">الطلب</span>
    <h1>تفاصيل الدفع</h1>
  </div>

  <!-- Flash messages -->
  @if (session('success'))
    <div class="flash-alert flash-success">✅ {{ session('success') }}</div>
  @endif
  @if (session('error'))
    <div class="flash-alert flash-error">❌ {{ session('error') }}</div>
  @endif

  <div class="row g-4">

    <!-- ═══ LEFT COLUMN ═══ -->
    <div class="col-lg-7">

      <!-- Products Table -->
      <div class="card-block">
        <div class="card-title">
          <span class="icon-circle">🛍</span>
          منتجات طلبك
        </div>

        <form id="order">
          <div style="overflow-x:auto;">
            <table class="product-table">
              <thead>
                <tr>
                  <th>وصف المنتج</th>
                  <th>السعر</th>
                  <th>العدد</th>
                </tr>
              </thead>
              <tbody>
                @foreach (Cart::instance('shopping')->content() as $item)
                <tr>
                  <input type="hidden" name="product_id[]"    value="{{ $item->id }}">
                  <input type="hidden" name="amount[]"        value="{{ $item->qty }}">
                  <input type="hidden" name="price[]"         value="{{ $item->price }}">
                  <input type="hidden" name="size[]"          value="{{ $item->options->size }}">
                  <input type="hidden" name="color[]"         value="{{ $item->options->color }}">
                  <input type="hidden" name="total_price[]"   value="{{ $item->subtotal() }}">
                  <td>
                    <a href="{{ route('user.product.show', $item->id) }}" class="product-name-link">
                      {{ $item->name }}
                    </a>
                  </td>
                  <td><span class="price-tag">{{ number_format($item->price, 2) }} جنيه</span></td>
                  <td><span class="qty-badge">× {{ $item->qty }}</span></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </form>
      </div>

      <!-- Shipping & Location -->
      <div class="card-block">
        <div class="card-title">
          <span class="icon-circle">📍</span>
          بيانات الشحن والتوصيل
        </div>

        <form id="location-data">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <div class="form-group-custom">
                <label class="form-label-custom" for="governorates">المحافظة</label>
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
                <input class="form-control-custom" id="address" name="address" placeholder="الشارع، الحي، المبنى..." />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group-custom">
                <label class="form-label-custom" for="user_name">الاسم ثلاثي (كما في البطاقة)</label>
                <input class="form-control-custom" id="user_name" name="user_name" placeholder="اسم المستلم" required />
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
                <label class="form-label-custom" for="temp_mobile">رقم الموبايل الاحتياطي</label>
                <input class="form-control-custom" type="number" id="temp_mobile" name="temp_mobile"
                  pattern="\d{11}" minlength="11" maxlength="11" placeholder="01xxxxxxxxx" required />
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- Shipping Method -->
      <div class="card-block">
        <div class="card-title">
          <span class="icon-circle">🚚</span>
          طريقة الاستلام
        </div>

        <div class="form-group-custom">
          <label class="form-label-custom" for="shipping_method">اختر طريقة الشحن</label>
          <select class="form-control-custom" id="shipping_method" name="shipping_method_id">
            <option value="">اختر طريقة الشحن</option>
          </select>
        </div>

        <!-- Shipping Details Panel -->
        <div id="shipping_info" style="display:none;">
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
              <a href="{{ route('user.fqa') }}" class="btn-confirm" style="padding:5px 12px; font-size:12px; border-radius:8px; text-decoration:none; display:inline-flex;">تعرف على طريقة الاستلام</a>
            </span>
          </div>
          <div class="info-row" id="address_row">
            <span class="info-label">العنوان</span>
            <span class="info-value" id="shipping_address">---</span>
          </div>
          <div class="info-row" id="phones_row">
            <span class="info-label">أرقام الهاتف</span>
            <span class="info-value" id="shipping_phones">---</span>
          </div>
        </div>

        <!-- Near Post -->
        <div id="nearpost_wrapper" style="display:none; margin-top:14px;">
          <label class="form-label-custom" for="near_post">اسم أقرب مكتب بريد</label>
          <input type="text" id="near_post" name="near_post" class="form-control-custom" placeholder="ادخل اسم مكتب البريد" />
        </div>
      </div>

    </div><!-- /col -->

    <!-- ═══ RIGHT COLUMN ═══ -->
    <div class="col-lg-5">

      <!-- Discount -->
      @php
        $cartSubtotal = (float) str_replace(',', '', Cart::subtotal());
        $discountAmount = session()->has('applied_discount') ? session('applied_discount')['amount'] : 0;
        $preShippingTotal = max($cartSubtotal - $discountAmount, 0);
      @endphp
      <input type="hidden" name="all_total" value="{{ $preShippingTotal }}" id="all_total">

      @if ($discountAmount > 0)
      <div class="discount-badge">
        <span class="disc-icon">🎉</span>
        <div>
          تم تطبيق الخصم بنجاح!<br>
          رمز الكوبون: <strong>{{ session('applied_discount')['code'] }}</strong> &nbsp;|&nbsp;
          قيمة الخصم: <strong>{{ $discountAmount }} جنيه</strong>
        </div>
      </div>
      @endif

      @if ($discountSetting && $discountSetting->discount_enabled)
      <div class="card-block" style="padding:20px 22px;">
        <div class="card-title" style="margin-bottom:12px;">
          <span class="icon-circle">🏷</span>
          كوبون الخصم
        </div>
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

      <!-- Order Summary -->
      <div class="card-block">
        <div class="card-title">
          <span class="icon-circle">🧾</span>
          ملخص الطلب
        </div>

        <div class="summary-row">
          <span class="s-label">إجمالي المنتجات</span>
          <span class="s-value">{{ number_format($cartSubtotal, 2) }} جنيه</span>
        </div>
        @if ($discountAmount > 0)
        <div class="summary-row">
          <span class="s-label">الخصم</span>
          <span class="s-value" style="color:var(--success);">- {{ $discountAmount }} جنيه</span>
        </div>
        @endif
        <div class="summary-row">
          <span class="s-label">مصاريف الشحن</span>
          <span class="s-value" id="delivery">—</span>
        </div>
        <div class="summary-row total-row">
          <span class="s-label">الإجمالي النهائي</span>
          <span class="s-value" id="all">—</span>
        </div>
      </div>

      <!-- Payment Methods -->
      <div class="accordion hidden" id="accordionExample">

        <div class="card-title" style="margin-bottom:14px; padding: 0 4px;">
          <span class="icon-circle">💳</span>
          طريقة الدفع
        </div>

        <!-- Fawry -->
        <div class="pay-accordion-item accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button pay-btn collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
              <img src="https://logos-download.com/wp-content/uploads/2023/02/Fawry_Logo.png" class="pay-logo">
              Fawry Pay
            </button>
          </h2>
          <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body pay-body">
              <p style="color:var(--text-muted); font-size:13px; margin-bottom:14px;">
                يتم إضافة رسوم 1% + 2.5 جنيه للدفع بفوري باي
              </p>
              <button type="button" class="btn-confirm" id="fawry">
                ✅ اضغط لإكمال عملية الدفع
              </button>
            </div>
          </div>
        </div>

        {{-- More payment items go here ... --}}

      </div>

      <!-- Contact Strip -->
      <div class="contact-strip">
        <p>لو قابلتك أي مشكلة، تواصل معنا على الفيس بوك أو الواتساب</p>
        <div class="contact-btns">
          <a href="https://wa.me/+201060683708" target="_blank" class="btn-contact btn-wa">
            <span>📲</span> واتساب
          </a>
          <a href="https://www.facebook.com/highacademy2?mibextid=ZbWKwL" target="_blank" class="btn-contact btn-fb">
            <span>📘</span> فيس بوك
          </a>
        </div>
      </div>

    </div><!-- /col -->
  </div><!-- /row -->
</div>

{{-- Hidden paragraph to store discounted subtotal for JavaScript --}}
<p id="total" style="display: none;">{{ $preShippingTotal }}</p>

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
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const allShippingMethods = @json($shippingMethods);
    const governoratesDataset = @json($governoratesData);
    const governoratesSelect = document.getElementById('governorates');
    const shippingSelect = document.getElementById('shipping_method');

    function updateShippingInfo() {
      const sel = shippingSelect.value;
      const m = allShippingMethods.find(x => x.id == sel);
      const info = document.getElementById('shipping_info');
      const wrapper = document.getElementById('nearpost_wrapper');
      const npInput = document.getElementById('near_post');
      const fee_row = document.getElementById('fee_row');
      const homeRow = document.getElementById('home_cost_row');
      const postRow = document.getElementById('post_cost_row');
      const homeValue = document.getElementById('home_shipping_cost');
      const postValue = document.getElementById('post_shipping_cost');

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

      if (m.type === 'branch') {
        document.getElementById('shipping_fee').innerText = (Number(m.fee) * TOTAL_QUANTITY).toFixed(2);
      } else {
        document.getElementById('shipping_fee').innerText = Number(m.fee).toFixed(2);
      }

      const govId = governoratesSelect.value;
      const matchedGov = governoratesDataset.find(g => g.id == govId);
      const rawHomeBase = matchedGov ? (matchedGov.home_shipping_price ?? matchedGov.price) : null;
      const rawPostBase = matchedGov ? (matchedGov.post_shipping_price ?? matchedGov.price) : null;
      const homeBase = rawHomeBase !== null && rawHomeBase !== undefined ? Number(rawHomeBase) : NaN;
      const postBase = rawPostBase !== null && rawPostBase !== undefined ? Number(rawPostBase) : NaN;
      const baseFee = Number(m.fee ?? 0);

      if (homeRow && postRow) {
        homeRow.style.display = 'none';
        postRow.style.display = 'none';
        if (m.type === 'home' && govId) {
          homeRow.style.display = 'flex';
          homeValue.innerText = (baseFee + homeBase + TAX_HOME).toFixed(2);
          if (Number.isFinite(postBase)) {
            postRow.style.display = 'flex';
            postValue.innerText = (baseFee + postBase + TAX_POST).toFixed(2);
          }
        } else if (m.type === 'post' && govId) {
          postRow.style.display = 'flex';
          postValue.innerText = (baseFee + postBase + TAX_POST).toFixed(2);
          if (Number.isFinite(homeBase)) {
            homeRow.style.display = 'flex';
            homeValue.innerText = (baseFee + homeBase + TAX_HOME).toFixed(2);
          }
        }
      }

      const addr = document.getElementById('address_row');
      const phone = document.getElementById('phones_row');
      if (m.type === 'branch') {
        fee_row.style.display = 'flex';
        addr.style.display = 'flex';
        phone.style.display = 'flex';
        document.getElementById('shipping_address').innerText = m.address;
        document.getElementById('shipping_phones').innerText = (m.phones || []).join(', ');
      } else {
        fee_row.style.display = 'none';
        addr.style.display = 'none';
        phone.style.display = 'none';
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
          const opt = document.createElement('option');
          opt.value = m.id;
          const label = { branch: 'استلام من المكتبة', home: 'شحن لباب البيت', post: 'شحن لمكتب بريد' }[m.type] + ' — ' + m.name;
          opt.textContent = label;
          shippingSelect.appendChild(opt);
        }
      });
      if (shippingSelect.options.length === 1) {
        const opt = document.createElement('option');
        opt.value = '';
        opt.disabled = true;
        opt.textContent = allShippingMethods.length === 0
          ? 'لا توجد طريقة شحن متاحة للمنتجات الحالية'
          : (governoratesSelect.value ? 'لا توجد طريقة شحن متاحة لهذه المحافظة' : 'اختر المحافظة لعرض طرق الشحن المتاحة');
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
  const shippingMethods = @json($shippingMethods);
  const governoratesData = @json($governoratesData);
  const citiesData = @json($citiesData);
  const shippingSelect = document.getElementById('shipping_method');

  function setupPaymentHandler(buttonId, routeUrl, extraFormId = null) {
    $(buttonId).click(function() {
      const btn = $(this);
      const originalText = btn.text();
      btn.prop('disabled', true).text('جاري المعالجة...');

      const selectedMethod = shippingMethods.find(m => m.id == shippingSelect.value);
      const nearPostInput = document.getElementById('near_post');

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
  setupPaymentHandler('#fawry', "{{ route('fawry.pay') }}");
  setupPaymentHandler('#wallet', "{{ route('fawry.wallet.pay') }}", '#ewallets-form');
  setupPaymentHandler('#insta-pay', "{{ route('manual.pay') }}", '#instapay-form');

  function calculateTotal() {
    const discountedSubtotal = parseFloat(document.getElementById("total").innerText.trim()) || 0;
    const shippingMethodId = parseInt(document.getElementById("shipping_method").value);
    const governorateId = parseInt(document.getElementById("governorates").value);
    const method = shippingMethods.find(m => m.id === shippingMethodId);
    let grandTotal = discountedSubtotal;

    if (!method) { updateCostRows(null, 0); return; }

    let deliveryFee = Number(method.fee ?? 0);
    const gov = governoratesData.find(g => g.id == governorateId);
    const rawHomeBase = gov ? (gov.home_shipping_price ?? gov.price) : null;
    const rawPostBase = gov ? (gov.post_shipping_price ?? gov.price) : null;
    const homeBase = rawHomeBase !== null && rawHomeBase !== undefined ? Number(rawHomeBase) : NaN;
    const postBase = rawPostBase !== null && rawPostBase !== undefined ? Number(rawPostBase) : NaN;
    let appliedTax = 0;

    if (method.type === 'home' && Number.isNaN(homeBase)) { updateCostRows(null, 0); return; }
    if (method.type === 'post' && Number.isNaN(postBase)) { updateCostRows(null, 0); return; }

    if (method.type === 'home') {
      appliedTax = TAX_HOME;
      deliveryFee += (Number.isNaN(homeBase) ? 0 : homeBase) + appliedTax;
      updateCostRows('home', deliveryFee);
    } else if (method.type === 'post') {
      appliedTax = TAX_POST;
      deliveryFee += (Number.isNaN(postBase) ? 0 : postBase) + appliedTax;
      updateCostRows('post', deliveryFee);
    } else {
      deliveryFee = deliveryFee * TOTAL_QUANTITY;
      updateCostRows('branch', deliveryFee);
    }

    grandTotal = discountedSubtotal + deliveryFee;

    document.querySelectorAll("#delivery").forEach(el => { el.innerText = `جنيه ${deliveryFee.toFixed(2)}`; });
    document.querySelectorAll("#all").forEach(el => { el.innerText = `جنيه ${grandTotal.toFixed(2)}`; });
    const shippingTaxEl = document.getElementById('shippingTax');
    if (shippingTaxEl) shippingTaxEl.innerText = `جنيه ${appliedTax.toFixed(2)}`;
  }

  function updateCostRows(type, cost) {
    const homeRow = document.getElementById('home_cost_row');
    const postRow = document.getElementById('post_cost_row');
    const homeValue = document.getElementById('home_shipping_cost');
    const postValue = document.getElementById('post_shipping_cost');
    if (!homeRow || !postRow) return;
    homeRow.style.display = 'none';
    postRow.style.display = 'none';
    if (!type || !Number.isFinite(cost)) return;
    if (type === 'home') { homeRow.style.display = 'flex'; homeValue.innerText = cost.toFixed(2); }
    else if (type === 'post') { postRow.style.display = 'flex'; postValue.innerText = cost.toFixed(2); }
  }

  document.getElementById('shipping_method').addEventListener('change', function() { calculateTotal(); });
</script>

<?php
  $addressId = null; $citiId = null;
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
      const citiesSelect = document.getElementById('cities');
      citiesSelect.innerHTML = '<option value="">اختر المدينة</option>';
      if (governorateId) {
        citiesSelect.disabled = false;
        citiesData.filter(city => city.governorate_id == governorateId).forEach(city => {
          const option = document.createElement('option');
          option.value = city.id;
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
      const governorate = document.getElementById('governorates').value;
      const city = document.getElementById('cities').value;
      const shippingMethodId = document.getElementById('shipping_method').value;
      const selectedMethod = shippingMethods.find(m => m.id == shippingMethodId);
      let show = false;
      if (selectedMethod) {
        if (selectedMethod.type === 'branch') show = true;
        else if (governorate && city) show = true;
      }
      if (show) accordion.classList.remove('hidden');
      else accordion.classList.add('hidden');
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
    confirmButtonText: "حسنًا",
    showCloseButton: true
  }).then((result) => {
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

<script type="text/javascript">
  function showPreview(event) {
    if (event.target.files.length > 0) {
      var src = URL.createObjectURL(event.target.files[0]);
      var preview = document.getElementById("file-ip-1-preview");
      preview.src = src;
      preview.style.display = "block";
    }
  }
  function showPreview2(event) {
    if (event.target.files.length > 0) {
      var src = URL.createObjectURL(event.target.files[0]);
      var preview = document.getElementById("file-ip-2-preview");
      preview.src = src;
      preview.style.display = "block";
    }
  }
</script>
@endsection