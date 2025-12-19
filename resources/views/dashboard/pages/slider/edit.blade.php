@extends('dashboard.layouts.layoutMaster')

@section('title', 'تعديل صف دراسي')

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
            <span class="text-muted fw-light">الصفوف الدراسية /</span> تعديل صف دراسي
        </h4>
    </div>

    <form action="{{ route('dashboard.slider.update') }}" method="POST" data-validate class="needs-validation" novalidate
        data-ajax data-redirect="{{ route('dashboard.slider') }}" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="slider_id" value="{{ $slider->id }}">

        <div class="card mb-4">
            <h5 class="card-header">بيانات الصف الدراسي</h5>
            <div class="card-body">
                <div class="tab-content pt-3">
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach (config('translatable.locales') as $locale)
                            <li class="nav-item">
                                <a class="nav-link @if ($loop->first) active @endif" data-bs-toggle="tab"
                                    href="#slider-{{ $locale }}" role="tab">
                                    {{ strtoupper($locale) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    @foreach (config('translatable.locales') as $locale)
                        <div class="tab-pane fade @if ($loop->first) show active @endif mt-3"
                            id="slider-{{ $locale }}" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">عنوان الصف الدراسي ({{ strtoupper($locale) }})</label>
                                    <input type="text" class="form-control" name="title:{{ $locale }}"
                                        value="{{ optional($slider->translate($locale))->title }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <h5 class="card-header">ربط المرحلة التعليمية</h5>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">المرحلة الدراسية</label>
                        <select name="stage_id" id="stage" class="form-select">
                            <option value="">اختر المرحلة</option>
                            @foreach ($stages as $s)
                                <option value="{{ $s->id }}" @if (isset($slider->stage) && $slider->stage->id == $s->id) selected @endif>
                                    {{ $s->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-end">
                <button type="submit" class="btn btn-primary">تحديث الصف الدراسي</button>
            </div>
        </div>

    </form>

@endsection
