@extends('dashboard.layouts.layoutMaster')

@section('title', 'الباركود - اختيار طريقة الشحن')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Page Header -->
        <div class="text-center mb-5">
            <h3 class="mb-2">
                <i class="ti ti-barcode me-2"></i>إدارة الباركود
            </h3>
            <p class="text-muted">اختر طريقة الشحن لعرض الطلبات وإدارة الباركود</p>
        </div>

        <!-- Shipping Method Cards -->
        <div class="row justify-content-center g-4">

            <!-- All Orders Card -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 cursor-pointer card-hover"
                    onclick="window.location.href='{{ route('dashboard.orders.barcode.list') }}?shipping=all'">
                    <div class="card-body text-center p-4">
                        <div class="avatar avatar-lg mx-auto mb-3 bg-label-primary">
                            <i class="ti ti-list ti-lg"></i>
                        </div>
                        <h5 class="card-title mb-2">جميع الطلبات</h5>
                        <p class="card-text text-muted small">عرض كافة الطلبات بجميع طرق الشحن</p>
                    </div>
                </div>
            </div>

            @php
                $shippingTypes = \App\Models\ShippingMethod::select('type')->distinct()->get();
                $typeLabels = [
                    'branch' => 'استلام من الفرع',
                    'post' => 'البريد المصري',
                    'home' => 'التوصيل للمنزل',
                ];
                $typeIcons = [
                    'branch' => 'ti ti-building-store',
                    'post' => 'ti ti-mail',
                    'home' => 'ti ti-home',
                ];
                $typeColors = [
                    'branch' => 'success',
                    'post' => 'info',
                    'home' => 'warning',
                ];
            @endphp

            @foreach ($shippingTypes as $type)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card h-100 cursor-pointer card-hover"
                        onclick="window.location.href='{{ route('dashboard.orders.barcode.list') }}?shipping={{ $type->type }}'">
                        <div class="card-body text-center p-4">
                            <div
                                class="avatar avatar-lg mx-auto mb-3 bg-label-{{ $typeColors[$type->type] ?? 'secondary' }}">
                                <i class="{{ $typeIcons[$type->type] ?? 'ti ti-truck' }} ti-lg"></i>
                            </div>
                            <h5 class="card-title mb-2">{{ $typeLabels[$type->type] ?? $type->type }}</h5>
                            <p class="card-text text-muted small">عرض طلبات {{ $typeLabels[$type->type] ?? $type->type }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection

@section('page-style')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .card-hover {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-color: var(--bs-primary);
        }

        .avatar {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
    </style>
@endsection
