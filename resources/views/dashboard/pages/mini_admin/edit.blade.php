@extends('dashboard.layouts.layoutMaster')

@section('title', 'تعديل أدمن فرعي')

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
            <span class="text-muted fw-light">حسابات الأدمن الفرعي /</span> تعديل أدمن فرعي
        </h4>
    </div>

    <form action="{{ route('dashboard.minadmin.update') }}" method="POST" data-validate class="needs-validation" novalidate
        data-ajax data-redirect="{{ route('dashboard.minadmin') }}" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="min_admin_id" value="{{ $miniAdmin->id }}">

        <div class="card mb-4">
            <h5 class="card-header">بيانات الأدمن الفرعي</h5>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">الاسم</label>
                        <input type="text" name="name" class="form-control" value="{{ $miniAdmin->name }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" value="{{ $miniAdmin->email }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">كلمة السر (اتركها فارغة إذا لم ترغب في التغيير)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-end">
                <button type="submit" class="btn btn-primary">تحديث الأدمن الفرعي</button>
            </div>
        </div>

    </form>

@endsection
