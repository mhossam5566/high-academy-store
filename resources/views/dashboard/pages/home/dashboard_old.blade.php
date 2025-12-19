@extends('dashboard.layouts/layoutMaster')

@section('title', 'Analytics')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/swiper/swiper.css') }}" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('dashboard/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('dashboard/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/css/pages/cards-advance.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/swiper/swiper.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('dashboard/assets/js/dashboards-analytics.js') }}"></script>
@endsection

@section('content')

    <div class="row">
        <!-- Website Analytics -->
        <div class="col-lg-6 mb-4">
            <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg"
                id="swiper-with-pagination-cards">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-white mb-0 mt-2">Sales Overview</h5>
                                <small>Total Conversion Rate: 42.6%</small>
                            </div>
                            <div class="row">
                                <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                                    <h6 class="text-white mt-0 mt-md-3 mb-3">Traffic</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex mb-4 align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">164</p>
                                                    <p class="mb-0">Orders</p>
                                                </li>
                                                <li class="d-flex align-items-center mb-2">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">1,254</p>
                                                    <p class="mb-0">Students</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex mb-4 align-items-center">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">24</p>
                                                    <p class="mb-0">Teachers</p>
                                                </li>
                                                <li class="d-flex align-items-center mb-2">
                                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg">342</p>
                                                    <p class="mb-0">Courses</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                                    <img src="0" alt="Website Analytics" width="170"
                                        class="card-website-analytics-img">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-white mb-0 mt-2">Education Analytics</h5>
                                <small>Total Classes: 128</small>
                            </div>
                            <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                                <h6 class="text-white mt-0 mt-md-3 mb-3">Education Team</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex mb-4 align-items-center">
                                                <p class="mb-0 fw-medium me-2 website-analytics-text-bg">24</p>
                                                <p class="mb-0">Teachers</p>
                                            </li>
                                            <li class="d-flex align-items-center mb-2">
                                                <p class="mb-0 fw-medium me-2 website-analytics-text-bg">98</p>
                                                <p class="mb-0">Completed</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex mb-4 align-items-center">
                                                <p class="mb-0 fw-medium me-2 website-analytics-text-bg">12</p>
                                                <p class="mb-0">Stages</p>
                                            </li>
                                            <li class="d-flex align-items-center mb-2">
                                                <p class="mb-0 fw-medium me-2 website-analytics-text-bg">32</p>
                                                <p class="mb-0">Pending</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                                <img src="0" alt="Website Analytics" width="170"
                                    class="card-website-analytics-img">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-white mb-0 mt-2">Revenue Sources</h5>
                                <small>Monthly Revenue: 24,580 EGP</small>
                            </div>
                            <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                                <h6 class="text-white mt-0 mt-md-3 mb-3">Sources</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex mb-4 align-items-center">
                                                <p class="mb-0 fw-medium me-2 website-analytics-text-bg">24</p>
                                                <p class="mb-0">Teachers</p>
                                            </li>
                                            <li class="d-flex align-items-center mb-2">
                                                <p class="mb-0 fw-medium me-2 website-analytics-text-bg">342</p>
                                                <p class="mb-0">Courses</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex mb-4 align-items-center">
                                                <p class="mb-0 fw-medium me-2 website-analytics-text-bg">128</p>
                                                <p class="mb-0">Lectures</p>
                                            </li>
                                            <li class="d-flex align-items-center mb-2">
                                                <p class="mb-0 fw-medium me-2 website-analytics-text-bg">1254</p>
                                                <p class="mb-0">Students</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                                <img src="0" alt="Website Analytics" width="170"
                                    class="card-website-analytics-img">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <!--/ Website Analytics -->

        <!-- Sales Overview -->
        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <small class="d-block mb-1 text-muted">Sales Overview</small>
                        <p class="card-text text-success">+42.6%</p>
                    </div>
                    <h4 class="card-title mb-1">24.5k EGP</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="d-flex gap-2 align-items-center mb-2">
                                <span class="badge bg-label-info p-1 rounded"><i
                                        class="ti ti-shopping-cart ti-xs"></i></span>
                                <p class="mb-0">Orders</p>
                            </div>
                            <h5 class="mb-0 pt-1 text-nowrap">59.8%</h5>
                            <small class="text-muted">98</small>
                        </div>
                        <div class="col-4">
                            <div class="divider divider-vertical">
                                <div class="divider-text">
                                    <span class="badge-divider-bg bg-label-secondary">VS</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="d-flex gap-2 justify-content-end align-items-center mb-2">
                                <p class="mb-0">Students</p>
                                <span class="badge bg-label-primary p-1 rounded"><i class="ti ti-users ti-xs"></i></span>
                            </div>
                            <h5 class="mb-0 pt-1 text-nowrap ms-lg-n3 ms-xl-0">764.2%</h5>
                            <small class="text-muted">1,254</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-4">
                        <div class="progress w-100" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: 60%" role="progressbar"></div>
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 40%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Sales Overview -->

        <!-- Revenue Generated -->
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="card-icon">
                        <span class="badge bg-label-success rounded-pill p-2">
                            <i class='ti ti-credit-card ti-sm'></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-0 mt-2">24.5k</h5>
                    <small>Monthly Revenue</small>
                </div>
                <div id="revenueGenerated"></div>
            </div>
        </div>
        <!--/ Revenue Generated -->

        <!-- Earning Reports -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                    <div class="card-title mb-0">
                        <h5 class="mb-0">Dashboard</h5>
                        <small class="text-muted">Dashboard</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="earningReportsId" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReportsId">
                            <a class="dropdown-item" href="0">Dashboard</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4 d-flex flex-column align-self-end">
                            <div class="d-flex gap-2 align-items-center mb-2 pb-1 flex-wrap">
                                <h1 class="mb-0">0</h1>
                                <div class="badge rounded 0">
                                    00%</div>
                            </div>
                            <small>Dashboard</small>
                        </div>
                        <div class="col-12 col-md-8">
                            <div id="weeklyEarningReports"></div>
                        </div>
                    </div>
                    <div class="border rounded p-3 mt-4">
                        <div class="row gap-4 gap-sm-0">
                            <div class="col-12 col-sm-4">
                                <div class="d-flex gap-2 align-items-center">
                                    <div class="badge rounded bg-label-primary p-1"><i
                                            class="ti ti-currency-dollar ti-sm"></i></div>
                                    <h6 class="mb-0">Dashboard</h6>
                                </div>
                                <h4 class="my-2 pt-1">0</h4>
                                <div class="progress w-75" style="height:4px">
                                    <div class="progress-bar" role="progressbar" style="width: 100%"></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="d-flex gap-2 align-items-center">
                                    <div class="badge rounded bg-label-info p-1"><i class="ti ti-shopping-cart ti-sm"></i>
                                    </div>
                                    <h6 class="mb-0">Dashboard</h6>
                                </div>
                                <h4 class="my-2 pt-1">0</h4>
                                <div class="progress w-75" style="height:4px">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 75%"></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="d-flex gap-2 align-items-center">
                                    <div class="badge rounded bg-label-warning p-1"><i class="ti ti-clock ti-sm"></i>
                                    </div>
                                    <h6 class="mb-0">Dashboard</h6>
                                </div>
                                <h4 class="my-2 pt-1">0</h4>
                                <div class="progress w-75" style="height:4px">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 75%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Earning Reports -->

        <!-- Support Tracker -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between pb-0">
                    <div class="card-title mb-0">
                        <h5 class="mb-0">Dashboard</h5>
                        <small class="text-muted">Dashboard</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="supportTrackerMenu" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="supportTrackerMenu">
                            <a class="dropdown-item" href="0">Dashboard</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-4 col-md-12 col-lg-4">
                            <div class="mt-lg-4 mt-lg-2 mb-lg-4 mb-2 pt-1">
                                <h1 class="mb-0">0</h1>
                                <p class="mb-0">Dashboard</p>
                            </div>
                            <ul class="p-0 m-0">
                                <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">
                                    <div class="badge rounded bg-label-success p-1"><i class="ti ti-check ti-sm"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-nowrap">Dashboard</h6>
                                        <small class="text-muted">0</small>
                                    </div>
                                </li>
                                <li class="d-flex gap-3 align-items-center mb-lg-3 pb-1">
                                    <div class="badge rounded bg-label-warning p-1"><i class="ti ti-clock ti-sm"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-nowrap">Dashboard</h6>
                                        <small class="text-muted">0</small>
                                    </div>
                                </li>
                                <li class="d-flex gap-3 align-items-center pb-1">
                                    <div class="badge rounded bg-label-info p-1"><i class="ti ti-package ti-sm"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-nowrap">Dashboard</h6>
                                        <small class="text-muted">0</small>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-sm-8 col-md-12 col-lg-8">
                            <div id="supportTracker"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Support Tracker -->

        <!-- Sales By Country -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Dashboard</h5>
                        <small class="text-muted">Dashboard</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="salesByCountry" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="salesByCountry">
                            <a class="dropdown-item" href="0">Dashboard</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <li class="d-flex align-items-center">
                            <img src="0" alt="Restaurant 1" class="rounded-circle me-3" width="34"
                                height="34" style="object-fit: cover;">
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">Restaurant 1</h6>
                                    </div>
                                    <small class="text-muted">Cairo</small>
                                </div>
                                <div class="user-progress">
                                    <p class="text-success fw-medium mb-0 d-flex justify-content-center gap-1">
                                        <i class='ti ti-package'></i>
                                        120 Orders
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex align-items-center">
                            <img src="0" alt="Restaurant 2" class="rounded-circle me-3" width="34"
                                height="34" style="object-fit: cover;">
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">Restaurant 2</h6>
                                    </div>
                                    <small class="text-muted">Giza</small>
                                </div>
                                <div class="user-progress">
                                    <p class="text-success fw-medium mb-0 d-flex justify-content-center gap-1">
                                        <i class='ti ti-package'></i>
                                        95 Orders
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex align-items-center">
                            <img src="0" alt="Restaurant 3" class="rounded-circle me-3" width="34"
                                height="34" style="object-fit: cover;">
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">Restaurant 3</h6>
                                    </div>
                                    <small class="text-muted">Alexandria</small>
                                </div>
                                <div class="user-progress">
                                    <p class="text-success fw-medium mb-0 d-flex justify-content-center gap-1">
                                        <i class='ti ti-package'></i>
                                        80 Orders
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--/ Sales By Country -->

        <!-- Total Earning -->
        <div class="col-12 col-xl-4 mb-4 col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between pb-1">
                    <h5 class="mb-0 card-title">Dashboard</h5>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="totalEarning" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalEarning">
                            <a class="dropdown-item" href="0">Dashboard</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h1 class="mb-0 me-2">0%
                        </h1>
                        <i class="ti ti-chevron-0 text-0 me-1"></i>
                        <p class="text-0 mb-0">
                            0%</p>
                    </div>
                    <div id="totalEarningChart"></div>
                    <div class="d-flex align-items-start my-4">
                        <div class="badge rounded bg-label-primary p-2 me-3 rounded"><i
                                class="ti ti-shopping-cart ti-sm"></i></div>
                        <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                            <div class="me-2">
                                <h6 class="mb-0">Dashboard</h6>
                                <small class="text-muted">Dashboard</small>
                            </div>
                            <p class="mb-0 text-success">0</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="badge rounded bg-label-success p-2 me-3 rounded"><i
                                class="ti ti-currency-dollar ti-sm"></i></div>
                        <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                            <div class="me-2">
                                <h6 class="mb-0">Dashboard</h6>
                                <small class="text-muted">Dashboard</small>
                            </div>
                            <p class="mb-0 text-success">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Total Earning -->

        <!-- Monthly Campaign State -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="mb-0">Dashboard</h5>
                        <small class="text-muted">Dashboard</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="MonthlyCampaign" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="MonthlyCampaign">
                            <a class="dropdown-item" href="0">Dashboard</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                            <div class="badge bg-label-success rounded p-2"><i class="ti ti-check ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                <h6 class="mb-0 ms-3">Dashboard</h6>
                                <div class="d-flex">
                                    <p class="mb-0 fw-medium">0</p>
                                    <p class="ms-3 text-success mb-0">
                                        0%</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                            <div class="badge bg-label-warning rounded p-2"><i class="ti ti-clock ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                <h6 class="mb-0 ms-3">Dashboard</h6>
                                <div class="d-flex">
                                    <p class="mb-0 fw-medium">0</p>
                                    <p class="ms-3 text-warning mb-0">
                                        0%</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                            <div class="badge bg-label-info rounded p-2"><i class="ti ti-truck ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                <h6 class="mb-0 ms-3">Dashboard</h6>
                                <div class="d-flex">
                                    <p class="mb-0 fw-medium">0</p>
                                    <p class="ms-3 text-info mb-0">
                                        0%</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                            <div class="badge bg-label-primary rounded p-2"><i class="ti ti-users ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                <h6 class="mb-0 ms-3">Dashboard</h6>
                                <div class="d-flex">
                                    <p class="mb-0 fw-medium">0</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                            <div class="badge bg-label-secondary rounded p-2"><i class="ti ti-building-store ti-sm"></i>
                            </div>
                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                <h6 class="mb-0 ms-3">Dashboard</h6>
                                <div class="d-flex">
                                    <p class="mb-0 fw-medium">0</p>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="badge bg-label-info rounded p-2"><i class="ti ti-motorbike ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                <h6 class="mb-0 ms-3">Dashboard</h6>
                                <div class="d-flex">
                                    <p class="mb-0 fw-medium">0</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--/ Monthly Campaign State -->

        <!-- Recent Orders -->
        <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mb-4 mb-lg-0">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Dashboard</h5>
                        <small class="text-muted">Dashboard</small>
                    </div>
                    <div>
                        <a href="0" class="btn btn-sm btn-primary">
                            Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Dashboard</th>
                                    <th>Dashboard</th>
                                    <th>Dashboard</th>
                                    <th>Dashboard</th>
                                    <th>Dashboard</th>
                                    <th>Dashboard</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>#1001</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    A
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-nowrap">Ahmed Ali</h6>
                                                <small class="text-muted">ahmed@example.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">12 Dec 2025</small>
                                    </td>
                                    <td><strong>450 EGP</strong></td>
                                    <td>
                                        <span class="badge bg-label-success">Paid</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">Online</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#1002</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    S
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-nowrap">Sara Mohamed</h6>
                                                <small class="text-muted">sara@example.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">13 Dec 2025</small>
                                    </td>
                                    <td><strong>320 EGP</strong></td>
                                    <td>
                                        <span class="badge bg-label-warning">Pending</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">Cash</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>#1003</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    M
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-nowrap">Mostafa Salah</h6>
                                                <small class="text-muted">mostafa@example.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">14 Dec 2025</small>
                                    </td>
                                    <td><strong>275 EGP</strong></td>
                                    <td>
                                        <span class="badge bg-label-danger">Canceled</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">Online</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Recent Orders -->
    </div>

@endsection
