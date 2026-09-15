@extends('dashboard.layouts.layoutMaster')

@section('title', 'لوحة التحكم - الإحصائيات')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        @if ($canViewStats)
            <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">
                    <i class="ti ti-dashboard me-2"></i>لوحة التحكم
                </h4>
                <p class="text-muted mb-0">مرحباً بك في نظام إدارة الطلبات</p>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="ti ti-calendar me-1"></i>من تاريخ
                        </label>
                        <input type="date" class="form-control" name="date_from" value="{{ $dateFrom }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="ti ti-calendar me-1"></i>إلى تاريخ
                        </label>
                        <input type="date" class="form-control" name="date_to" value="{{ $dateTo }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="ti ti-filter me-1"></i>تصفية
                        </button>
                        <a href="{{ route('dashboard.index') }}" class="btn btn-label-secondary">
                            <i class="ti ti-refresh me-1"></i>إعادة تعيين
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Key Statistics -->
        <div class="row mb-4">
            <!-- Total Products -->
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted d-block mb-1">إجمالي المنتجات</span>
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 me-2">{{ number_format($statistics['products']['total']) }}</h3>
                                </div>
                                <small class="text-success">
                                    <i class="ti ti-circle-check"></i>
                                    {{ $statistics['products']['active'] }} نشط
                                </small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-package ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted d-block mb-1">إجمالي العملاء</span>
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 me-2">{{ number_format($statistics['users']['total']) }}</h3>
                                </div>
                                <small class="text-info">
                                    <i class="ti ti-user-plus"></i>
                                    {{ $statistics['users']['new_this_month'] }} جديد هذا الشهر
                                </small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ti ti-users ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Order Value -->
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted d-block mb-1">متوسط قيمة الطلب</span>
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 me-2">{{ number_format($statistics['revenue']['average'], 2) }}</h3>
                                </div>
                                <small class="text-muted">جنيه</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ti ti-calculator ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Growth Rate -->
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted d-block mb-1">معدل النمو</span>
                                <div class="d-flex align-items-center">
                                    <h3
                                        class="mb-0 me-2 {{ $statistics['revenue']['growth'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format(abs($statistics['revenue']['growth']), 1) }}%
                                    </h3>
                                </div>
                                <small
                                    class="{{ $statistics['revenue']['growth'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i
                                        class="ti {{ $statistics['revenue']['growth'] >= 0 ? 'ti-trending-up' : 'ti-trending-down' }}"></i>
                                    مقارنة بالفترة السابقة
                                </small>
                            </div>
                            <div class="avatar">
                                <span
                                    class="avatar-initial rounded bg-label-{{ $statistics['revenue']['growth'] >= 0 ? 'success' : 'danger' }}">
                                    <i class="ti ti-chart-line ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Cards -->
        <div class="row mb-4">
            <!-- Total Revenue -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted">إجمالي الإيرادات</span>
                                <div class="d-flex align-items-center my-2">
                                    <h3 class="mb-0 me-2">{{ number_format($statistics['revenue']['total'], 2) }}</h3>
                                    <span class="text-success">جنيه</span>
                                </div>
                                <small class="mb-0">من الطلبات المدفوعة</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ti ti-currency-pound ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today Revenue -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted">إيرادات اليوم</span>
                                <div class="d-flex align-items-center my-2">
                                    <h3 class="mb-0 me-2">{{ number_format($statistics['revenue']['today'], 2) }}</h3>
                                    <span class="text-success">جنيه</span>
                                </div>
                                <small class="mb-0">مبيعات {{ now()->format('Y/m/d') }}</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ti ti-calendar-stats ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Week Revenue -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted">إيرادات الأسبوع</span>
                                <div class="d-flex align-items-center my-2">
                                    <h3 class="mb-0 me-2">{{ number_format($statistics['revenue']['week'], 2) }}</h3>
                                    <span class="text-success">جنيه</span>
                                </div>
                                <small class="mb-0">آخر 7 أيام</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-calendar-week ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Month Revenue -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="content-left">
                                <span class="text-muted">إيرادات الشهر</span>
                                <div class="d-flex align-items-center my-2">
                                    <h3 class="mb-0 me-2">{{ number_format($statistics['revenue']['month'], 2) }}</h3>
                                    <span class="text-success">جنيه</span>
                                </div>
                                <small class="mb-0">شهر {{ now()->format('m/Y') }}</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ti ti-chart-line ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Statistics -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-shopping-cart me-2"></i>إحصائيات الطلبات العادية
                        </h5>
                        <a href="{{ route('dashboard.orders') }}" class="btn btn-sm btn-primary">
                            <i class="ti ti-eye me-1"></i>عرض الكل
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Total Orders -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            <i class="ti ti-package ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ $statistics['orders']['total'] }}</h4>
                                        <small class="text-muted">إجمالي الطلبات</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Orders -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-warning">
                                            <i class="ti ti-clock ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ $statistics['orders']['pending'] }}</h4>
                                        <small class="text-muted">قيد الانتظار</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Delivered Orders -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-success">
                                            <i class="ti ti-circle-check ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ $statistics['orders']['delivered'] }}</h4>
                                        <small class="text-muted">تم التوصيل</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Cancelled Orders -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-danger">
                                            <i class="ti ti-circle-x ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ $statistics['orders']['cancelled'] }}</h4>
                                        <small class="text-muted">ملغية</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="row mt-3">
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-label-success">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-0">{{ $statistics['orders']['paid'] }}</h5>
                                                <small>طلبات مدفوعة</small>
                                            </div>
                                            <i class="ti ti-check ti-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-label-warning">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-0">{{ $statistics['orders']['unpaid'] }}</h5>
                                                <small>طلبات غير مدفوعة</small>
                                            </div>
                                            <i class="ti ti-alert-circle ti-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Voucher Orders Statistics -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-ticket me-2"></i>إحصائيات طلبات الكوبونات
                        </h5>
                        <a href="{{ route('dashboard.voucher_order') }}" class="btn btn-sm btn-primary">
                            <i class="ti ti-eye me-1"></i>عرض الكل
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Total Voucher Orders -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            <i class="ti ti-ticket ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ $statistics['vouchers']['total'] }}</h4>
                                        <small class="text-muted">إجمالي الطلبات</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Vouchers -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-warning">
                                            <i class="ti ti-clock ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ $statistics['vouchers']['pending'] }}</h4>
                                        <small class="text-muted">قيد الانتظار</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Success Vouchers -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-info">
                                            <i class="ti ti-check ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ $statistics['vouchers']['success'] }}</h4>
                                        <small class="text-muted">ناجحة</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Completed Vouchers -->
                            <div class="col-lg-3 col-sm-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial rounded bg-label-success">
                                            <i class="ti ti-circle-check ti-md"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ $statistics['vouchers']['completed'] }}</h4>
                                        <small class="text-muted">مكتملة</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="row">
            <!-- Recent Regular Orders -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-clock me-2"></i>آخر الطلبات العادية
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>العميل</th>
                                        <th>الإجمالي</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($statistics['orders']['recent'] as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('dashboard.orders.details', $order->id) }}"
                                                    class="text-primary">
                                                    #{{ $order->id }}
                                                </a>
                                            </td>
                                            <td>{{ $order->user->name ?? ($order->name ?? 'N/A') }}</td>
                                            <td>{{ number_format($order->total, 2) }} جنيه</td>
                                            <td>
                                                @if ($order->status == 'delivered')
                                                    <span class="badge bg-secondary">تم التوصيل</span>
                                                @elseif($order->status == 'pending')
                                                    <span class="badge bg-secondary">قيد الانتظار</span>
                                                @elseif($order->status == 'cancelled')
                                                    <span class="badge bg-secondary">ملغي</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $order->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $order->created_at->format('Y/m/d') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="ti ti-inbox ti-lg"></i>
                                                <p class="mb-0 mt-2">لا توجد طلبات حالياً</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Voucher Orders -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-clock me-2"></i>آخر طلبات الكوبونات
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>العميل</th>
                                        <th>الكمية</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($statistics['vouchers']['recent'] as $voucher)
                                        <tr>
                                            <td>
                                                <a href="{{ route('dashboard.voucher_order.details', $voucher->id) }}"
                                                    class="text-primary">
                                                    #{{ $voucher->id }}
                                                </a>
                                            </td>
                                            <td>{{ $voucher->user_name }}</td>
                                            <td>{{ $voucher->quantity }}</td>
                                            <td>
                                                @if ($voucher->state == 'completed')
                                                    <span class="badge bg-success">مكتملة</span>
                                                @elseif($voucher->state == 'success')
                                                    <span class="badge bg-info">ناجحة</span>
                                                @elseif($voucher->state == 'pending')
                                                    <span class="badge bg-warning">قيد الانتظار</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $voucher->state }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $voucher->created_at->format('Y/m/d') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="ti ti-inbox ti-lg"></i>
                                                <p class="mb-0 mt-2">لا توجد طلبات حالياً</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-chart-bar me-2"></i>الإيرادات الشهرية
                        </h5>
                        <span class="badge bg-label-primary">آخر 6 شهور</span>
                    </div>
                    <div class="card-body">
                        <div id="revenueChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Count Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-chart-line me-2"></i>عدد الطلبات الشهرية
                        </h5>
                        <span class="badge bg-label-success">آخر 6 شهور</span>
                    </div>
                    <div class="card-body">
                        <div id="ordersCountChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Orders Status Chart -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-chart-pie me-2"></i>توزيع حالات الطلبات
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="ordersStatusChart"></div>
                    </div>
                </div>
            </div>

            <!-- Vouchers Status Chart -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-chart-donut me-2"></i>توزيع حالات الكوبونات
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="vouchersStatusChart"></div>
                    </div>
                </div>
            </div>
        </div>

        @else
            @php
                $currentAdmin = auth('admin')->user();
                $roles = $currentAdmin ? $currentAdmin->roles : collect();
                $hasAnyPermission = false;
            @endphp

            <!-- Welcome Banner -->
            <div class="card mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-radius: 12px;">
                <div class="card-body p-4 text-white">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar avatar-xl">
                                @if($currentAdmin && $currentAdmin->photo)
                                    <img src="{{ asset('images/admin/' . $currentAdmin->photo) }}" alt="Avatar" class="rounded-circle" style="width: 58px; height: 58px; object-fit: cover; border: 2px solid rgba(255,255,255,0.3);">
                                @else
                                    <span class="avatar-initial rounded-circle bg-primary fs-3 fw-bold" style="width: 58px; height: 58px; display: flex; align-items: center; justify-content: center; border: 2px solid rgba(255,255,255,0.3);">
                                        {{ mb_substr($currentAdmin->name ?? 'A', 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-white mb-1 fw-bold">
                                    مرحباً بك، {{ $currentAdmin->name ?? 'المدير' }} 👋
                                </h4>
                                <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                                    @forelse($roles as $role)
                                        <span class="badge bg-primary bg-opacity-75 px-3 py-1 text-white">
                                            <i class="ti ti-shield-check me-1"></i>{{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="badge bg-secondary px-3 py-1">مدير بدون دور</span>
                                    @endforelse
                                    <span class="text-white-50 small ms-1">
                                        <i class="ti ti-mail me-1"></i>{{ $currentAdmin->email ?? '' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-md-end">
                            <span class="badge bg-light bg-opacity-10 text-white border border-light border-opacity-25 px-3 py-2">
                                <i class="ti ti-calendar-event me-1"></i>{{ now()->locale('ar')->translatedFormat('l، d F Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Header for Accessible Modules -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="ti ti-apps me-2"></i>الأقسام والمهام المصرح لك بها
                    </h5>
                    <small class="text-muted">الوصول المباشر للوظائف المتاحة لحسابك</small>
                </div>
            </div>

            <!-- Modules Grid Based on Admin's Permissions -->
            <div class="row g-4 mb-4">

                {{-- الحجز من المكتبة --}}
                @if ($currentAdmin && ($currentAdmin->hasRole('Super Admin') || $currentAdmin->can('create_library_orders')))
                    @php $hasAnyPermission = true; @endphp
                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm" style="border-top: 4px solid #10b981 !important;">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="avatar avatar-md">
                                            <span class="avatar-initial rounded bg-label-success">
                                                <i class="ti ti-building-store fs-4"></i>
                                            </span>
                                        </div>
                                        <span class="badge bg-label-success">متاح لك</span>
                                    </div>
                                    <h5 class="card-title mb-2 fw-bold">الحجز من المكتبة</h5>
                                    <p class="text-muted small mb-3">
                                        إنشاء وتسجيل طلبات الكتب والملازم يدوياً للطلاب الحاضرين بمقر المكتبة مع طباعة الفاتورة والباركود.
                                    </p>
                                </div>
                                <div>
                                    <a href="{{ route('dashboard.orders.library_booking') }}" class="btn btn-success w-100 fw-bold">
                                        <i class="ti ti-plus me-1"></i>تسجيل طلب جديد من المكتبة
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- إدارة الطلبات --}}
                @if ($currentAdmin && ($currentAdmin->hasRole('Super Admin') || $currentAdmin->can('view_orders')))
                    @php $hasAnyPermission = true; @endphp
                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm" style="border-top: 4px solid #3b82f6 !important;">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="avatar avatar-md">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                <i class="ti ti-shopping-cart fs-4"></i>
                                            </span>
                                        </div>
                                        <span class="badge bg-label-primary">متاح لك</span>
                                    </div>
                                    <h5 class="card-title mb-2 fw-bold">إدارة كل الطلبات</h5>
                                    <p class="text-muted small mb-3">
                                        استعراض قائمة طلبات المتجر، متابعة الحالات، وتحديث الشحن والتوصيل.
                                    </p>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <a href="{{ route('dashboard.orders') }}" class="btn btn-primary w-100">
                                        <i class="ti ti-list me-1"></i>عرض قائمة الطلبات
                                    </a>
                                    @if ($currentAdmin->can('barcode_orders'))
                                        <a href="{{ route('dashboard.orders.barcode') }}" class="btn btn-label-secondary w-100 btn-sm">
                                            <i class="ti ti-barcode me-1"></i>إدارة باركود الطلبات
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- الكتب والمنتجات --}}
                @if ($currentAdmin && ($currentAdmin->hasRole('Super Admin') || $currentAdmin->can('view_products')))
                    @php $hasAnyPermission = true; @endphp
                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm" style="border-top: 4px solid #f59e0b !important;">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="avatar avatar-md">
                                            <span class="avatar-initial rounded bg-label-warning">
                                                <i class="ti ti-books fs-4"></i>
                                            </span>
                                        </div>
                                        <span class="badge bg-label-warning">متاح لك</span>
                                    </div>
                                    <h5 class="card-title mb-2 fw-bold">الكتب والمذكرات</h5>
                                    <p class="text-muted small mb-3">
                                        استعراض الكتب والملازم الدراسية، متابعة الأسعار، المخزون، والتصنيفات.
                                    </p>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <a href="{{ route('dashboard.product') }}" class="btn btn-warning w-100 text-white">
                                        <i class="ti ti-book me-1"></i>عرض الكتب
                                    </a>
                                    @if ($currentAdmin->can('create_products'))
                                        <a href="{{ route('dashboard.create.product') }}" class="btn btn-label-secondary w-100 btn-sm">
                                            <i class="ti ti-plus me-1"></i>إضافة كتاب جديد
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- طلبات الكوبونات والفاوتشرات --}}
                @if ($currentAdmin && ($currentAdmin->hasRole('Super Admin') || $currentAdmin->hasAnyPermission(['view_voucher_orders', 'view_vouchers', 'view_coupons'])))
                    @php $hasAnyPermission = true; @endphp
                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm" style="border-top: 4px solid #8b5cf6 !important;">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="avatar avatar-md">
                                            <span class="avatar-initial rounded bg-label-info" style="color: #8b5cf6 !important; background-color: rgba(139, 92, 246, 0.1) !important;">
                                                <i class="ti ti-ticket fs-4"></i>
                                            </span>
                                        </div>
                                        <span class="badge bg-label-info">متاح لك</span>
                                    </div>
                                    <h5 class="card-title mb-2 fw-bold">الفاوتشرات والكوبونات</h5>
                                    <p class="text-muted small mb-3">
                                        متابعة طلبات كروت الكوبونات والفاوتشرات المسجلة وتحديث حالاتها.
                                    </p>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    @if ($currentAdmin->can('view_voucher_orders'))
                                        <a href="{{ route('dashboard.voucher_order') }}" class="btn btn-primary w-100" style="background-color: #8b5cf6; border-color: #8b5cf6;">
                                            <i class="ti ti-file-text me-1"></i>طلبات الكوبونات
                                        </a>
                                    @endif
                                    @if ($currentAdmin->can('view_vouchers'))
                                        <a href="{{ route('dashboard.voucher') }}" class="btn btn-label-secondary w-100 btn-sm">
                                            <i class="ti ti-ticket me-1"></i>قائمة الفاوتشرات
                                        </a>
                                    @endif
                                    @if ($currentAdmin->can('view_coupons'))
                                        <a href="{{ route('dashboard.coupons') }}" class="btn btn-label-secondary w-100 btn-sm">
                                            <i class="ti ti-discount me-1"></i>أكواد المدرسين
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- المدرسين --}}
                @if ($currentAdmin && ($currentAdmin->hasRole('Super Admin') || $currentAdmin->can('view_teachers')))
                    @php $hasAnyPermission = true; @endphp
                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm" style="border-top: 4px solid #06b6d4 !important;">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="avatar avatar-md">
                                            <span class="avatar-initial rounded bg-label-info">
                                                <i class="ti ti-users fs-4"></i>
                                            </span>
                                        </div>
                                        <span class="badge bg-label-info">متاح لك</span>
                                    </div>
                                    <h5 class="card-title mb-2 fw-bold">المدرسين والمحاضرين</h5>
                                    <p class="text-muted small mb-3">
                                        إدارة حسابات المدرسين، المواد الخاصة بهم، وربطهم بالمراحل الدراسية.
                                    </p>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <a href="{{ route('dashboard.teachers') }}" class="btn btn-info w-100 text-white">
                                        <i class="ti ti-user-check me-1"></i>عرض قائمة المدرسين
                                    </a>
                                    @if ($currentAdmin->can('create_teachers'))
                                        <a href="{{ route('dashboard.create.teachers') }}" class="btn btn-label-secondary w-100 btn-sm">
                                            <i class="ti ti-plus me-1"></i>إضافة مدرس جديد
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- المراحل والمواد الدراسية --}}
                @if ($currentAdmin && ($currentAdmin->hasRole('Super Admin') || $currentAdmin->hasAnyPermission(['view_stages', 'view_sliders', 'view_categories', 'view_main_categories'])))
                    @php $hasAnyPermission = true; @endphp
                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm" style="border-top: 4px solid #6366f1 !important;">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="avatar avatar-md">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                <i class="ti ti-school fs-4"></i>
                                            </span>
                                        </div>
                                        <span class="badge bg-label-primary">متاح لك</span>
                                    </div>
                                    <h5 class="card-title mb-2 fw-bold">الهيكل التعليمي</h5>
                                    <p class="text-muted small mb-3">
                                        إدارة المراحل التعليمية، الصفوف الدراسية، المواد، والأقسام الرئيسية.
                                    </p>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    @if ($currentAdmin->can('view_stages'))
                                        <a href="{{ route('dashboard.education_stages') }}" class="btn btn-sm btn-outline-primary flex-fill">
                                            المراحل التعليمية
                                        </a>
                                    @endif
                                    @if ($currentAdmin->can('view_sliders'))
                                        <a href="{{ route('dashboard.slider') }}" class="btn btn-sm btn-outline-primary flex-fill">
                                            الصفوف الدراسية
                                        </a>
                                    @endif
                                    @if ($currentAdmin->can('view_categories'))
                                        <a href="{{ route('dashboard.category') }}" class="btn btn-sm btn-outline-primary flex-fill">
                                            المواد الدراسية
                                        </a>
                                    @endif
                                    @if ($currentAdmin->can('view_main_categories'))
                                        <a href="{{ route('dashboard.main_categories') }}" class="btn btn-sm btn-outline-primary flex-fill">
                                            الأقسام الرئيسية
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- العروض والخصومات --}}
                @if ($currentAdmin && ($currentAdmin->hasRole('Super Admin') || $currentAdmin->hasAnyPermission(['view_offers', 'view_discounts'])))
                    @php $hasAnyPermission = true; @endphp
                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm" style="border-top: 4px solid #ef4444 !important;">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="avatar avatar-md">
                                            <span class="avatar-initial rounded bg-label-danger">
                                                <i class="ti ti-tag fs-4"></i>
                                            </span>
                                        </div>
                                        <span class="badge bg-label-danger">متاح لك</span>
                                    </div>
                                    <h5 class="card-title mb-2 fw-bold">العروض والخصومات</h5>
                                    <p class="text-muted small mb-3">
                                        إدارة العروض الترويجية والخصومات المطبقة على أسعار الكتب.
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    @if ($currentAdmin->can('view_offers'))
                                        <a href="{{ route('dashboard.offers') }}" class="btn btn-danger flex-fill">العروض</a>
                                    @endif
                                    @if ($currentAdmin->can('view_discounts'))
                                        <a href="{{ route('dashboard.discounts') }}" class="btn btn-label-secondary flex-fill">الخصومات</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- الشحن والمحافظات --}}
                @if ($currentAdmin && ($currentAdmin->hasRole('Super Admin') || $currentAdmin->hasAnyPermission(['view_shipping_methods', 'view_governorates'])))
                    @php $hasAnyPermission = true; @endphp
                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm" style="border-top: 4px solid #14b8a6 !important;">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="avatar avatar-md">
                                            <span class="avatar-initial rounded bg-label-info">
                                                <i class="ti ti-truck fs-4"></i>
                                            </span>
                                        </div>
                                        <span class="badge bg-label-info">متاح لك</span>
                                    </div>
                                    <h5 class="card-title mb-2 fw-bold">الشحن والمحافظات</h5>
                                    <p class="text-muted small mb-3">
                                        إدارة طرق وشركات الشحن، أسعار التوصيل للمحافظات والمناطق.
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    @if ($currentAdmin->can('view_shipping_methods'))
                                        <a href="{{ route('dashboard.shipping_methods.index') }}" class="btn btn-info text-white flex-fill">طرق الشحن</a>
                                    @endif
                                    @if ($currentAdmin->can('view_governorates'))
                                        <a href="{{ route('dashboard.governorates.index') }}" class="btn btn-label-secondary flex-fill">المحافظات والمدن</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- الأسئلة الشائعة والبحث --}}
                @if ($currentAdmin && ($currentAdmin->hasRole('Super Admin') || $currentAdmin->hasAnyPermission(['view_faqs', 'view_search_keywords', 'view_notifications'])))
                    @php $hasAnyPermission = true; @endphp
                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm" style="border-top: 4px solid #64748b !important;">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="avatar avatar-md">
                                            <span class="avatar-initial rounded bg-label-secondary">
                                                <i class="ti ti-help fs-4"></i>
                                            </span>
                                        </div>
                                        <span class="badge bg-label-secondary">متاح لك</span>
                                    </div>
                                    <h5 class="card-title mb-2 fw-bold">المحتوى والتنبيهات</h5>
                                    <p class="text-muted small mb-3">
                                        إدارة الأسئلة الشائعة، الكلمات الأكثر بحثاً، وتنبيهات الموقع للزوار.
                                    </p>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    @if ($currentAdmin->can('view_faqs'))
                                        <a href="{{ route('dashboard.faqs.index') }}" class="btn btn-sm btn-outline-secondary flex-fill">الأسئلة الشائعة</a>
                                    @endif
                                    @if ($currentAdmin->can('view_search_keywords'))
                                        <a href="{{ route('dashboard.search-keywords.index') }}" class="btn btn-sm btn-outline-secondary flex-fill">كلمات البحث</a>
                                    @endif
                                    @if ($currentAdmin->can('view_notifications'))
                                        <a href="{{ route('dashboard.site_notifications.index') }}" class="btn btn-sm btn-outline-secondary flex-fill">تنبيهات الموقع</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- إدارة المديرين والأدوار --}}
                @if ($currentAdmin && ($currentAdmin->hasRole('Super Admin') || $currentAdmin->hasAnyPermission(['view_admins', 'view_roles', 'view_settings'])))
                    @php $hasAnyPermission = true; @endphp
                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm" style="border-top: 4px solid #e11d48 !important;">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="avatar avatar-md">
                                            <span class="avatar-initial rounded bg-label-danger">
                                                <i class="ti ti-shield-lock fs-4"></i>
                                            </span>
                                        </div>
                                        <span class="badge bg-label-danger">متاح لك</span>
                                    </div>
                                    <h5 class="card-title mb-2 fw-bold">النظام والإدارة</h5>
                                    <p class="text-muted small mb-3">
                                        إدارة حسابات المديرين، توزيع الأدوار والصلاحيات، وإعدادات الموقع.
                                    </p>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    @if ($currentAdmin->can('view_admins'))
                                        <a href="{{ route('dashboard.admins.index') }}" class="btn btn-sm btn-danger flex-fill">قائمة المديرين</a>
                                    @endif
                                    @if ($currentAdmin->can('view_roles'))
                                        <a href="{{ route('dashboard.roles.index') }}" class="btn btn-sm btn-label-secondary flex-fill">الأدوار والصلاحيات</a>
                                    @endif
                                    @if ($currentAdmin->can('view_settings'))
                                        <a href="{{ route('dashboard.settings.index') }}" class="btn btn-sm btn-label-secondary flex-fill">إعدادات الموقع</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- Fallback if no specific permissions --}}
            @if (!$hasAnyPermission)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center py-5">
                        <div class="avatar avatar-xl mx-auto mb-3">
                            <span class="avatar-initial rounded-circle bg-label-warning fs-1">
                                <i class="ti ti-alert-triangle"></i>
                            </span>
                        </div>
                        <h4 class="fw-bold mb-2">لا توجد صلاحيات مخصصة لحسابك حالياً</h4>
                        <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">
                            تم تسجيل دخولك بنجاح ولكن لم يتم تعيين صلاحيات وصول بعد. يرجى التواصل مع المسؤول العام (Super Admin) لمنحك الصلاحيات المناسبة لمهامك.
                        </p>
                        <a href="{{ route('dashboard.profile') }}" class="btn btn-primary">
                            <i class="ti ti-user me-1"></i>عرض الملف الشخصي
                        </a>
                    </div>
                </div>
            @endif

            {{-- Recent Orders Table if admin has view_orders permission --}}
            @if ($currentAdmin && ($currentAdmin->hasRole('Super Admin') || $currentAdmin->can('view_orders')) && isset($recentOrders) && $recentOrders->count() > 0)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center bg-transparent py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="ti ti-clock me-2 text-primary"></i>آخر الطلبات المسجلة
                        </h5>
                        <a href="{{ route('dashboard.orders') }}" class="btn btn-sm btn-label-primary">
                            <i class="ti ti-eye me-1"></i>عرض كل الطلبات
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>العميل</th>
                                        <th>الإجمالي</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                        <th>إجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('dashboard.orders.details', $order->id) }}" class="fw-bold text-primary">
                                                    #{{ $order->id }}
                                                </a>
                                            </td>
                                            <td>{{ $order->user->name ?? ($order->name ?? 'N/A') }}</td>
                                            <td><span class="fw-bold">{{ number_format($order->total, 2) }}</span> جنيه</td>
                                            <td>
                                                @if ($order->status == 'delivered')
                                                    <span class="badge bg-label-success">تم التوصيل</span>
                                                @elseif($order->status == 'pending')
                                                    <span class="badge bg-label-warning">قيد الانتظار</span>
                                                @elseif($order->status == 'cancelled')
                                                    <span class="badge bg-label-danger">ملغي</span>
                                                @else
                                                    <span class="badge bg-label-secondary">{{ $order->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $order->created_at->format('Y/m/d H:i') }}</td>
                                            <td>
                                                <a href="{{ route('dashboard.orders.details', $order->id) }}" class="btn btn-xs btn-label-primary">
                                                    التفاصيل
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Recent Voucher Orders Table if admin has view_voucher_orders permission --}}
            @if ($currentAdmin && ($currentAdmin->hasRole('Super Admin') || $currentAdmin->can('view_voucher_orders')) && isset($recentVoucherOrders) && $recentVoucherOrders->count() > 0)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center bg-transparent py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="ti ti-ticket me-2 text-info"></i>آخر طلبات الكوبونات
                        </h5>
                        <a href="{{ route('dashboard.voucher_order') }}" class="btn btn-sm btn-label-info">
                            <i class="ti ti-eye me-1"></i>عرض الكل
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>العميل</th>
                                        <th>الكمية</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                        <th>إجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentVoucherOrders as $voucher)
                                        <tr>
                                            <td>
                                                <a href="{{ route('dashboard.voucher_order.details', $voucher->id) }}" class="fw-bold text-info">
                                                    #{{ $voucher->id }}
                                                </a>
                                            </td>
                                            <td>{{ $voucher->user_name }}</td>
                                            <td>{{ $voucher->quantity }}</td>
                                            <td>
                                                @if ($voucher->state == 'completed')
                                                    <span class="badge bg-label-success">مكتملة</span>
                                                @elseif($voucher->state == 'success')
                                                    <span class="badge bg-label-info">ناجحة</span>
                                                @elseif($voucher->state == 'pending')
                                                    <span class="badge bg-label-warning">قيد الانتظار</span>
                                                @else
                                                    <span class="badge bg-label-secondary">{{ $voucher->state }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $voucher->created_at->format('Y/m/d H:i') }}</td>
                                            <td>
                                                <a href="{{ route('dashboard.voucher_order.details', $voucher->id) }}" class="btn btn-xs btn-label-info">
                                                    التفاصيل
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        @endif
    </div>
@endsection

@section('page-script')
    @if ($canViewStats)
    <script>
        // Revenue Chart
        const revenueChartOptions = {
            series: [{
                name: 'الإيرادات',
                data: [
                    @foreach ($statistics['monthly'] as $month)
                        {{ number_format($month['revenue'], 0, '', '') }},
                    @endforeach
                ]
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: ['#696cff'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: [
                    @foreach ($statistics['monthly'] as $month)
                        '{{ $month['month_ar'] }}',
                    @endforeach
                ],
                labels: {
                    style: {
                        fontSize: '13px'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return val.toLocaleString() + ' جنيه';
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 5
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val.toLocaleString() + ' جنيه';
                    }
                }
            }
        };

        // Orders Count Chart
        const ordersCountChartOptions = {
            series: [{
                name: 'عدد الطلبات',
                data: [
                    @foreach ($statistics['monthly'] as $month)
                        {{ $month['orders'] }},
                    @endforeach
                ]
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    columnWidth: '45%',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                offsetY: -25,
                style: {
                    fontSize: '12px',
                    colors: ['#304758']
                }
            },
            colors: ['#56ca00'],
            xaxis: {
                categories: [
                    @foreach ($statistics['monthly'] as $month)
                        '{{ $month['month_ar'] }}',
                    @endforeach
                ],
                labels: {
                    style: {
                        fontSize: '13px'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return Math.round(val);
                    }
                },
                title: {
                    text: 'عدد الطلبات'
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 5
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + ' طلب';
                    }
                }
            }
        };

        // Orders Status Chart
        const ordersStatusChart = {
            series: [
                {{ $statistics['orders']['pending'] }},
                {{ $statistics['orders']['delivered'] }},
                {{ $statistics['orders']['cancelled'] }}
            ],
            labels: ['قيد الانتظار', 'تم التوصيل', 'ملغي'],
            chart: {
                type: 'donut',
                height: 300
            },
            colors: ['#ffab00', '#56ca00', '#ff4c51'],
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            value: {
                                fontSize: '24px',
                                fontWeight: 600
                            },
                            total: {
                                show: true,
                                label: 'الإجمالي',
                                fontSize: '14px',
                                formatter: function(w) {
                                    return {{ $statistics['orders']['total'] }}
                                }
                            }
                        }
                    }
                }
            }
        };

        // Vouchers Status Chart
        const vouchersStatusChart = {
            series: [
                {{ $statistics['vouchers']['pending'] }},
                {{ $statistics['vouchers']['success'] }},
                {{ $statistics['vouchers']['completed'] }}
            ],
            labels: ['قيد الانتظار', 'ناجحة', 'مكتملة'],
            chart: {
                type: 'donut',
                height: 300
            },
            colors: ['#ffab00', '#16b1ff', '#56ca00'],
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            value: {
                                fontSize: '24px',
                                fontWeight: 600
                            },
                            total: {
                                show: true,
                                label: 'الإجمالي',
                                fontSize: '14px',
                                formatter: function(w) {
                                    return {{ $statistics['vouchers']['total'] }}
                                }
                            }
                        }
                    }
                }
            }
        };

        // Render Charts
        if (typeof ApexCharts !== 'undefined') {
            const revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueChartOptions);
            revenueChart.render();

            const ordersCountChart = new ApexCharts(document.querySelector("#ordersCountChart"), ordersCountChartOptions);
            ordersCountChart.render();

            const ordersChart = new ApexCharts(document.querySelector("#ordersStatusChart"), ordersStatusChart);
            ordersChart.render();

            const vouchersChart = new ApexCharts(document.querySelector("#vouchersStatusChart"), vouchersStatusChart);
            vouchersChart.render();
        }
    </script>
    @endif
@endsection
