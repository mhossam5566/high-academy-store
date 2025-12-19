@extends('dashboard.layouts.layoutMaster')

@section('title', 'إضافة مرحلة تعليمية')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/toastr/toastr.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/toastr/toastr.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('dashboard/assets/js/form-ajax.js') }}"></script>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">المراحل التعليمية /</span> إضافة مرحلة جديدة
        </h4>
    </div>

    <form action="{{ route('dashboard.store.stage') }}" method="POST" data-validate class="needs-validation" novalidate
        data-ajax data-redirect="{{ route('dashboard.education_stages') }}" enctype="multipart/form-data">
        @csrf

        <div class="card mb-4">
            <h5 class="card-header">بيانات المرحلة التعليمية</h5>
            <div class="card-body">
                <div class="tab-content pt-3">
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach (config('translatable.locales') as $locale)
                            <li class="nav-item">
                                <a class="nav-link @if ($loop->first) active @endif" data-bs-toggle="tab"
                                    href="#stage-{{ $locale }}" role="tab">
                                    {{ strtoupper($locale) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    @foreach (config('translatable.locales') as $locale)
                        <div class="tab-pane fade @if ($loop->first) show active @endif mt-3"
                            id="stage-{{ $locale }}" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">اسم المرحلة التعليمية ({{ strtoupper($locale) }})</label>
                                    <input type="text" class="form-control" name="title:{{ $locale }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <h5 class="card-header">إعدادات إضافية</h5>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label d-block mb-1">حالة التفعيل</label>
                        <label class="switch">
                            <input type="checkbox" class="switch-input" name="is_active" value="1" checked>
                            <span class="switch-toggle-slider">
                                <span class="switch-on"><i class="ti ti-check"></i></span>
                                <span class="switch-off"><i class="ti ti-x"></i></span>
                            </span>
                            <span class="switch-label">مفعل / غير مفعل</span>
                        </label>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label d-block mb-1">صورة المرحلة (اختياري)</label>
                        <input type="file" name="photo" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-end">
                <button type="submit" class="btn btn-primary">حفظ المرحلة</button>
            </div>
        </div>

    </form>

@endsection
