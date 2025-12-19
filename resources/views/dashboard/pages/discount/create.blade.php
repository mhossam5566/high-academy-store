@extends('dashboard.layouts.layoutMaster')

@section('title', 'إضافة قسيمة خصم')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <span class="text-muted fw-light">القسائم /</span> إضافة قسيمة جديدة
            </h4>
            <a href="{{ route('dashboard.discount') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
            </a>
        </div>

        <!-- Create Coupon Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-ticket me-2"></i>معلومات القسيمة
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="couponForm" method="POST" action="{{ route('dashboard.discount.store') }}">
                            @csrf

                            <!-- Coupon Code -->
                            <div class="mb-4">
                                <label class="form-label" for="code">
                                    <i class="ti ti-barcode me-1"></i>رمز القسيمة
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="code" name="code"
                                    class="form-control @error('code') is-invalid @enderror" required
                                    placeholder="مثال: SUMMER2025">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">رمز فريد لتعريف القسيمة</small>
                            </div>

                            <!-- Discount Amount -->
                            <div class="mb-4">
                                <label class="form-label" for="discount">
                                    <i class="ti ti-coin me-1"></i>قيمة الخصم (بالجنيه)
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="discount" name="discount"
                                    class="form-control @error('discount') is-invalid @enderror" required min="1"
                                    step="0.01" placeholder="مثال: 50">
                                @error('discount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">المبلغ المحدد الذي سيتم خصمه عند استخدام القسيمة</small>
                            </div>

                            <!-- Usage Limit -->
                            <div class="mb-4">
                                <label class="form-label" for="usage_limit">
                                    <i class="ti ti-users me-1"></i>حد الاستخدام
                                    <small class="text-muted">(اختياري)</small>
                                </label>
                                <input type="number" id="usage_limit" name="usage_limit"
                                    class="form-control @error('usage_limit') is-invalid @enderror" min="1"
                                    placeholder="مثال: 100">
                                @error('usage_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">عدد المرات التي يمكن استخدام القسيمة فيها (اتركه فارغاً لاستخدام
                                    غير محدود)</small>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ti ti-device-floppy me-1"></i>حفظ القسيمة
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
            $('#couponForm').submit(function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let submitBtn = $(this).find('button[type="submit"]');

                // Disable submit button
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span>جاري الحفظ...');

                $.ajax({
                    url: '{{ route('dashboard.discount.store') }}',
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحفظ',
                            text: 'تم حفظ القسيمة بنجاح',
                            confirmButtonText: 'موافق'
                        }).then(() => {
                            window.location.href = "{{ route('dashboard.discount') }}";
                        });
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html(
                            '<i class="ti ti-device-floppy me-1"></i>حفظ القسيمة');

                        let errorMessage = '';
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                errorMessage += value[0] + '<br>';
                            });
                        } else {
                            errorMessage = xhr.responseJSON?.error || xhr.responseText ||
                                'حدث خطأ ما!';
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
