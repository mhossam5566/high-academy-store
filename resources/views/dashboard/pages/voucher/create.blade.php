@extends('dashboard.layouts.layoutMaster')

@section('title', 'إضافة كود جديد')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">
@endsection

@section('vendor-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="{{ asset('dashboard/assets/js/form-ajax.js') }}"></script>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <span class="text-muted fw-light">أكواد {{ $coupon->name }} /</span> إضافة جديد
            </h4>
            <a href="{{ route('dashboard.vouchers', $coupon->id) }}" class="btn btn-secondary">
                <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-ticket me-2"></i>معلومات الكود
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('dashboard.vouchers.store', $coupon->id) }}"
                            enctype="multipart/form-data"
                            data-ajax
                            data-redirect="{{ route('dashboard.vouchers', $coupon->id) }}">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="ti ti-code me-1"></i>كود الكوبون
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="code"
                                    class="form-control @error('code') is-invalid @enderror" placeholder="أدخل كود الاشتراك"
                                    required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="ti ti-photo me-1"></i>صورة الكود
                                </label>
                                <input type="file" name="image" accept="image/*"
                                    class="dropify @error('image') is-invalid @enderror" data-height="300">
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">صورة الكوبون (اختياري)</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ti ti-device-floppy me-1"></i>حفظ الكود
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            $('.dropify').dropify({
                messages: {
                    default: 'اسحب الصورة هنا أو انقر للاختيار',
                    replace: 'اسحب الصورة أو انقر لاستبدالها',
                    remove: 'حذف',
                    error: 'حدث خطأ في تحميل الصورة'
                },
                error: {
                    fileSize: 'حجم الملف كبير جداً (الحد الأقصى 2 MB).'
                }
            });
        });
    </script>
@endsection
