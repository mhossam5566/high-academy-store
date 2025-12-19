@extends('dashboard.layouts.layoutMaster')

@section('title', 'تعديل كوبون')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('admin/assets/cssbundle/dropify.min.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('admin/assets/js/bundle/dropify.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">الكوبونات /</span> تعديل
        </h4>
        <a href="{{ route('dashboard.coupons') }}" class="btn btn-secondary">العودة للقائمة</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="couponForm" data-ajax data-redirect="{{ route('dashboard.coupons') }}"
                action="{{ route('dashboard.coupons.update', $coupon->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">اسم الكوبون</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ $coupon->name }}" data-validate placeholder="أدخل اسم الكوبون">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">السعر</label>
                        <input type="number" name="price" step="0.01"
                            class="form-control @error('price') is-invalid @enderror" value="{{ $coupon->price }}"
                            data-validate placeholder="أدخل السعر">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">النوع</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" data-validate>
                            <option value="">اختر النوع</option>
                            <option value="weekly" {{ $coupon->type == 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                            <option value="monthly" {{ $coupon->type == 'monthly' ? 'selected' : '' }}>شهري</option>
                            <option value="package" {{ $coupon->type == 'package' ? 'selected' : '' }}>باقة</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">الصورة</h6>
                                <input type="file" name="image" accept="image/*"
                                    data-default-file="{{ $coupon->image_path }}"
                                    class="dropify @error('image') is-invalid @enderror">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary">تحديث الكوبون</button>
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
