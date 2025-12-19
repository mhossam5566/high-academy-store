@extends('dashboard.layouts.layoutMaster')

@section('title', 'تعديل كود')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('admin/assets/cssbundle/dropify.min.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('admin/assets/js/bundle/dropify.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">أكواد الكوبونات /</span> تعديل
        </h4>
        <a href="{{ route('dashboard.vouchers', $voucher->coupon_id) }}" class="btn btn-secondary">العودة للقائمة</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="voucherForm" data-ajax data-redirect="{{ route('dashboard.vouchers', $voucher->coupon_id) }}"
                action="{{ route('dashboard.vouchers.update', $voucher->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label">كود الكوبون</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                            value="{{ $voucher->code }}" placeholder="أدخل كود الاشتراك">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">الصورة</h6>
                                <input type="file" name="image" accept="image/*"
                                    data-default-file="{{ $voucher->image_path }}"
                                    class="dropify @error('image') is-invalid @enderror">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary">تحديث الكود</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>
@endsection
