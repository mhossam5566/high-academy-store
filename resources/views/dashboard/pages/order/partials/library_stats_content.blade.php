{{-- Statistics Content Partial (Loaded synchronously or updated via AJAX) --}}
<div id="statsMainContainer">
    {{-- Header Banner & Period Indicator --}}
    <div class="card mb-4 border-0 shadow-sm bg-label-primary">
        <div class="card-body p-3 d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span class="badge bg-primary p-2 me-3 rounded">
                    <i class="ti ti-calendar-stats fs-3 text-white"></i>
                </span>
                <div>
                    <h5 class="mb-0 fw-bold text-primary">إحصائيات دورة: <span id="currentPeriodTitle">{{ $stats['period_label'] }}</span></h5>
                    <small class="text-muted">
                        من تاريخ: <strong>{{ $stats['from_date'] }}</strong> إلى تاريخ: <strong>{{ $stats['to_date'] }}</strong>
                        @if($stats['selected_status'] && $stats['selected_status'] !== 'all')
                            | الحالة: <span class="badge bg-label-info">{{ $stats['selected_status'] }}</span>
                        @endif
                    </small>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm btn-print-report" onclick="window.print();">
                    <i class="ti ti-printer me-1"></i>طباعة التقرير
                </button>
                <a href="{{ route('dashboard.orders.library_booking.stats.export', request()->all()) }}" 
                   id="btnExportExcel" class="btn btn-success btn-sm">
                    <i class="ti ti-file-spreadsheet me-1"></i>تصدير Excel
                </a>
            </div>
        </div>
    </div>

    {{-- KPI Highlights Row --}}
    <div class="row g-3 mb-4">
        {{-- Total Books Sold --}}
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-lg me-3">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ti ti-books fs-2"></i>
                        </span>
                    </div>
                    <div>
                        <span class="d-block text-muted small fw-semibold">إجمالي الكتب المباعة</span>
                        <h4 class="mb-0 fw-bold text-primary">{{ number_format($stats['grand_total_books']) }} <small class="fs-6 text-muted">كتاب</small></h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-lg me-3">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ti ti-cash fs-2"></i>
                        </span>
                    </div>
                    <div>
                        <span class="d-block text-muted small fw-semibold">إجمالي الإيرادات / المبيعات</span>
                        <h4 class="mb-0 fw-bold text-success">{{ number_format($stats['grand_total_revenue'], 2) }} <small class="fs-6 text-muted">ج.م</small></h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-lg me-3">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ti ti-shopping-cart fs-2"></i>
                        </span>
                    </div>
                    <div>
                        <span class="d-block text-muted small fw-semibold">إجمالي عدد الطلبات</span>
                        <h4 class="mb-0 fw-bold text-warning">{{ number_format($stats['grand_total_orders']) }} <small class="fs-6 text-muted">طلب</small></h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Selling Branch --}}
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-lg me-3">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ti ti-building-store fs-2"></i>
                        </span>
                    </div>
                    <div>
                        <span class="d-block text-muted small fw-semibold">الفرع الأكثر مبيعاً</span>
                        <h6 class="mb-0 fw-bold text-info text-truncate" style="max-width: 170px;" title="{{ $stats['top_branch'] }}">
                            {{ $stats['top_branch'] }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 1: Per-Branch Detailed Breakdown --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 fw-bold text-dark">
            <i class="ti ti-building me-2 text-primary"></i>تفاصيل المبيعات والأصناف لكل فرع
        </h5>
        <span class="badge bg-label-secondary">عدد الفروع: {{ count($stats['branches_stats']) }}</span>
    </div>

    <div class="row g-4 mb-4">
        @forelse($stats['branches_stats'] as $branch)
            @php
                $hasItems = !empty($branch['items']) && count($branch['items']) > 0;
                $pctOfTotal = $stats['grand_total_books'] > 0 
                    ? round(($branch['total_books'] / $stats['grand_total_books']) * 100, 1) 
                    : 0;
            @endphp
            <div class="col-12">
                <div class="card border shadow-sm">
                    {{-- Branch Header --}}
                    <div class="card-header bg-label-secondary d-flex flex-wrap justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary p-2 me-2 rounded-circle">
                                <i class="ti ti-building-store text-white fs-5"></i>
                            </span>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">{{ $branch['branch_name'] }}</h5>
                                <small class="text-muted"><i class="ti ti-map-pin me-1"></i>{{ $branch['branch_address'] }}</small>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-2 mt-md-0 align-items-center">
                            <span class="badge bg-label-primary fs-6 px-3 py-2">
                                <i class="ti ti-book me-1"></i>إجمالي الكتب: <strong class="ms-1">{{ number_format($branch['total_books']) }}</strong>
                            </span>
                            <span class="badge bg-label-success fs-6 px-3 py-2">
                                <i class="ti ti-coin me-1"></i>المبيعات: <strong class="ms-1">{{ number_format($branch['total_revenue'], 2) }} ج.م</strong>
                            </span>
                            <span class="badge bg-label-info fs-6 px-3 py-2">
                                <i class="ti ti-receipt me-1"></i>الطلبات: <strong class="ms-1">{{ $branch['orders_count'] }}</strong>
                            </span>
                            <span class="badge bg-dark text-white fs-6 px-2 py-2" title="نسبة مبيعات الفرع من إجمالي كل الفروع">
                                {{ $pctOfTotal }}% من الإجمالي
                            </span>
                        </div>
                    </div>

                    {{-- Branch Items Table --}}
                    <div class="card-body p-0">
                        @if($hasItems)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 5%;" class="text-center">#</th>
                                            <th style="width: 35%;">الكتاب / الصنف</th>
                                            <th style="width: 20%;">المدرس / المؤلف</th>
                                            <th style="width: 12%;" class="text-center">سعر الوحدة</th>
                                            <th style="width: 13%;" class="text-center">الكمية المباعة</th>
                                            <th style="width: 15%;" class="text-center">إجمالي المبيعات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($branch['items'] as $index => $item)
                                            @php
                                                $itemPct = $branch['total_books'] > 0 
                                                    ? round(($item['quantity'] / $branch['total_books']) * 100, 1) 
                                                    : 0;
                                            @endphp
                                            <tr>
                                                <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>
                                                <td>
                                                    <span class="fw-bold text-dark d-block">{{ $item['product_name'] }}</span>
                                                    <div class="progress mt-1" style="height: 4px; width: 120px;" title="يمثل {{ $itemPct }}% من مبيعات هذا الفرع">
                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $itemPct }}%"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-label-secondary">
                                                        <i class="ti ti-user me-1"></i>{{ $item['author'] }}
                                                    </span>
                                                </td>
                                                <td class="text-center font-monospace">{{ number_format($item['unit_price'], 2) }} ج.م</td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary px-3 py-2 fs-6 fw-bold">
                                                        {{ number_format($item['quantity']) }} نسخة
                                                    </span>
                                                    <small class="d-block text-muted mt-1">({{ $itemPct }}% من الفرع)</small>
                                                </td>
                                                <td class="text-center fw-bold text-success font-monospace fs-6">
                                                    {{ number_format($item['total_amount'], 2) }} ج.م
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light border-top">
                                        <tr class="fw-bold">
                                            <td colspan="4" class="text-end ps-3">إجمالي الفرع ({{ $branch['branch_name'] }}):</td>
                                            <td class="text-center text-primary fs-6">{{ number_format($branch['total_books']) }} كتاب</td>
                                            <td class="text-center text-success fs-6 font-monospace">{{ number_format($branch['total_revenue'], 2) }} ج.م</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="ti ti-package-off fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <span>لا توجد مبيعات مسجلة لهذا الفرع خلال الفترة المحددة ({{ $stats['period_label'] }}).</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center py-4">
                    <i class="ti ti-alert-triangle fs-2 d-block mb-2"></i>
                    لم يتم العثور على فروع مكتبة مسجلة في النظام.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Section 2: Consolidated Products Report across ALL Branches --}}
    <div class="card border shadow-sm mb-4">
        <div class="card-header bg-label-info py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 d-flex align-items-center text-info fw-bold">
                <i class="ti ti-list-details me-2 fs-4"></i>ملخص مبيعات الأصناف والكتب عبر جميع الفروع
            </h5>
            <span class="badge bg-info text-white">{{ count($stats['products_summary']) }} أصناف مباعة</span>
        </div>
        <div class="card-body p-0">
            @if(!empty($stats['products_summary']))
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;" class="text-center">#</th>
                                <th style="width: 30%;">اسم الكتاب / الصنف</th>
                                <th style="width: 15%;">المدرس / المؤلف</th>
                                <th style="width: 10%;" class="text-center">السعر</th>
                                <th style="width: 15%;" class="text-center">إجمالي الكمية المباعة</th>
                                <th style="width: 10%;" class="text-center">إجمالي المبيعات</th>
                                <th style="width: 15%;">توزيع المبيعات على الفروع</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['products_summary'] as $pIdx => $prod)
                                <tr>
                                    <td class="text-center text-muted fw-semibold">{{ $pIdx + 1 }}</td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $prod['product_name'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-secondary">{{ $prod['brand_name'] }}</span>
                                    </td>
                                    <td class="text-center font-monospace">{{ number_format($prod['unit_price'], 2) }} ج.م</td>
                                    <td class="text-center">
                                        <span class="badge bg-success px-3 py-2 fs-6 fw-bold">
                                            {{ number_format($prod['total_quantity']) }} نسخة
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold text-success font-monospace">
                                        {{ number_format($prod['total_revenue'], 2) }} ج.م
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($prod['branch_sales'] as $bName => $bQty)
                                                <span class="badge bg-label-primary small">
                                                    {{ $bName }}: <strong>{{ $bQty }}</strong>
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-dark">
                            <tr class="fw-bold">
                                <td colspan="4" class="text-end ps-3">الإجمالي العام لجميع الأصناف:</td>
                                <td class="text-center fs-6 text-white">{{ number_format($stats['grand_total_books']) }} كتاب</td>
                                <td class="text-center fs-6 text-white font-monospace">{{ number_format($stats['grand_total_revenue'], 2) }} ج.م</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="ti ti-books-off fs-1 d-block mb-2 text-secondary opacity-50"></i>
                    <h6>لا توجد مبيعات لأي أصناف في هذه الفترة المحددة</h6>
                    <small>يرجى اختيار دورة زمنية أخرى أو تعديل خيارات الفلتر أعلاه.</small>
                </div>
            @endif
        </div>
    </div>
</div>
