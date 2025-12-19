@extends('dashboard.layouts.layoutMaster')

@section('title', __('dashboard.items_management'))

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/toastr/toastr.css') }}">

@endsection

@section('vendor-script')

    <script src="{{ asset('dashboard/assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/toastr/toastr.js') }}"></script>
@endsection

@section('page-script')

    <script>
        CRUDHelper.init({
            tableSelector: '#brands-table',
            ajaxUrl: '{{ route('dashboard.items.data') }}',
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    'data': 'image',
                    'name': 'image',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'discount_type',
                    name: 'discount_type'
                },
                {
                    data: 'discount',
                    name: 'discount'
                },

                {
                    data: 'stock',
                    name: 'stock'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'is_express',
                    name: 'is_express'
                },
                {
                    data: 'category_and_brand',
                    name: 'category_and_brand',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
            ],

            delete: {
                selector: '.deleteItemBtn',
                url: '{{ route('dashboard.items.destroy', ':id') }}',
            },
        });
    </script>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">{{ __('dashboard.categories') }} /</span> {{ __('dashboard.manage') }}
        </h4>
        <a href="{{ route('dashboard.items.create') }}" class='btn btn-primary'>{{ __('dashboard.add_products') }}</a>
    </div>

    <div class="card">
        <h5 class="card-header">{{ __('dashboard.categories_table') }}</h5>
        <div class="card-datatable table-responsive">
            <table class="dt-responsive table" id="brands-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('dashboard.item_image') }}</th>
                        <th>{{ __('dashboard.item_name') }}</th>
                        <th>{{ __('dashboard.item_price') }}</th>
                        <th>{{ __('dashboard.discount_type') }}</th>
                        <th>{{ __('dashboard.discount_value') }}</th>
                        <th>{{ __('dashboard.item_stock') }}</th>
                        <th>{{ __('dashboard.item_status') }}</th>
                        <th>{{ __('dashboard.is_express') }}</th>
                        <th>{{ __('dashboard.category_and_brand') }}</th>
                        <th>{{ __('dashboard.created_at') }}</th>
                        <th>{{ __('dashboard.actions') }}</th>
                    </tr>
                </thead>

            </table>
        </div>
    </div>


@endsection
