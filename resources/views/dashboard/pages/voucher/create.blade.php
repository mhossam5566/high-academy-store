@extends('dashboard.layouts.layoutMaster')

@section('title', 'إضافة كود جديد')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
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
                        <form id="voucherForm" method="POST" action="{{ route('dashboard.vouchers.store', $coupon->id) }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="ti ti-code me-1"></i>كود الكوبون
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                    placeholder="أدخل كود الاشتراك" required>
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
            // Initialize Dropify
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

            $('#voucherForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let submitBtn = $(this).find('button[type="submit"]');

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span>جاري الحفظ...');

                $.ajax({
                    url: '{{ route('dashboard.vouchers.store', $coupon->id) }}',
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحفظ',
                            text: 'تم حفظ الكود بنجاح',
                            confirmButtonText: 'موافق'
                        }).then(() => {
                            window.location.href = "{{ route('dashboard.vouchers', $coupon->id) }}";
                        });
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html(
                            '<i class="ti ti-device-floppy me-1"></i>حفظ الكود');

                        let errorMessage = '';
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                errorMessage += value[0] + '<br>';
                            });
                        } else {
                            errorMessage = xhr.responseJSON?.error || xhr.responseText || 'حدث خطأ ما!';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            html: errorMessage,
                            confirmButtonText: 'موافق'
                        });
                    }
                });
            });
        });
    </script>
@endsection
