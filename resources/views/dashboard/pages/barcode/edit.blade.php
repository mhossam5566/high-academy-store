@extends('dashboard.layouts.layoutMaster')

@section('title', 'إضافة الباركود')

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
                <span class="text-muted fw-light">الباركود /</span> إضافة باركود للطلب #{{ $order->id }}
            </h4>
            <a href="{{ route('dashboard.orders.barcode.list') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-right me-1"></i>العودة
            </a>
        </div>

        <!-- Barcode Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-barcode me-2"></i>إضافة الباركود
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="form" class="needs-validation" novalidate>
                            @csrf

                            <!-- Order Info -->
                            <div class="alert alert-info mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-info-circle me-2 fs-4"></i>
                                    <div>
                                        <strong>معلومات الطلب:</strong>
                                        <div class="mt-1">
                                            <span class="badge bg-primary">رقم الطلب: {{ $order->id }}</span>
                                            <span class="badge bg-info">العميل: {{ $order->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Barcode Input -->
                            <div class="mb-4">
                                <label class="form-label" for="barcode">
                                    <i class="ti ti-barcode me-1"></i>رقم الباركود
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="barcode" id="barcode" value="{{ $order->barcode }}"
                                    class="form-control form-control-lg @error('barcode') is-invalid @enderror"
                                    placeholder="أدخل رقم الباركود" required autofocus>
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Hidden Order ID -->
                            <input type="hidden" name="id" value="{{ $order->id }}">

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button id="submit" type="submit" class="btn btn-primary btn-lg">
                                    <span class="btn-text">
                                        <i class="ti ti-device-floppy me-1"></i>حفظ الباركود
                                    </span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status"
                                        aria-hidden="true"></span>
                                    <span class="loading-text d-none">جاري الحفظ...</span>
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
            $('#form').submit(function(e) {
                e.preventDefault();

                // Validate form
                if (!this.checkValidity()) {
                    e.stopPropagation();
                    $(this).addClass('was-validated');
                    return;
                }

                let formData = new FormData(this);
                let submitBtn = $('#submit');

                // Show loading state
                submitBtn.prop('disabled', true);
                submitBtn.find('.btn-text').addClass('d-none');
                submitBtn.find('.spinner-border').removeClass('d-none');
                submitBtn.find('.loading-text').removeClass('d-none');

                $.ajax({
                    url: '{{ route('dashboard.orders.addbarcode') }}',
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function(response) {
                        resetButton();

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'نجح الحفظ',
                                text: response.msg || 'تم حفظ الباركود بنجاح',
                                confirmButtonText: 'موافق'
                            }).then(() => {
                                // Optionally redirect back
                                window.location.href =
                                    '{{ route('dashboard.orders.barcode.list') }}';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: response.msg || 'حدث خطأ أثناء الحفظ',
                                confirmButtonText: 'موافق'
                            });
                        }
                    },

                    error: function(xhr, status, error) {
                        resetButton();

                        let errorMessage = '';

                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            // Validation errors
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                errorMessage += value[0] + '<br>';
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ في التحقق من البيانات',
                                html: errorMessage,
                                confirmButtonText: 'موافق'
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.msg) {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: xhr.responseJSON.msg,
                                confirmButtonText: 'موافق'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ في الاتصال',
                                text: 'حدث خطأ أثناء الاتصال بالخادم. يرجى المحاولة مرة أخرى.',
                                confirmButtonText: 'موافق'
                            });
                        }
                    }
                });

                function resetButton() {
                    submitBtn.prop('disabled', false);
                    submitBtn.find('.btn-text').removeClass('d-none');
                    submitBtn.find('.spinner-border').addClass('d-none');
                    submitBtn.find('.loading-text').addClass('d-none');
                }
            });

            // Auto-focus on barcode input
            $('#barcode').focus();
        });
    </script>
@endsection
