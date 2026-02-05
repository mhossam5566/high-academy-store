@extends('dashboard.layouts.layoutMaster')

@section('title', 'تعديل كوبون')

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
                <span class="text-muted fw-light">الكوبونات /</span> تعديل #{{ $coupon->id }}
            </h4>
            <a href="{{ route('dashboard.coupons') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-ticket me-2"></i>تعديل معلومات الكوبون
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="couponForm" method="POST" action="{{ route('dashboard.coupons.update', $coupon->id) }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">
                                        <i class="ti ti-tag me-1"></i>اسم الكوبون
                                    </label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ $coupon->name }}" placeholder="أدخل اسم الكوبون">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label">
                                        <i class="ti ti-currency-dollar me-1"></i>السعر
                                    </label>
                                    <input type="number" name="price" step="0.01"
                                        class="form-control @error('price') is-invalid @enderror" value="{{ $coupon->price }}"
                                        placeholder="أدخل السعر">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-4">
                                    <label class="form-label">
                                        <i class="ti ti-category me-1"></i>النوع
                                    </label>
                                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                                        <option value="">اختر النوع</option>
                                        <option value="weekly" {{ $coupon->type == 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                                        <option value="monthly" {{ $coupon->type == 'monthly' ? 'selected' : '' }}>شهري</option>
                                        <option value="package" {{ $coupon->type == 'package' ? 'selected' : '' }}>باقة</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if($coupon->image)
                                <!-- Current Image Preview -->
                                <div class="col-12 mb-4">
                                    <label class="form-label">الصورة الحالية</label>
                                    <div class="text-center p-3 bg-light rounded" id="currentImageContainer">
                                        <img src="{{ $coupon->image_path }}" alt="Coupon Image"
                                            class="img-fluid rounded shadow-sm" style="max-width: 300px;">
                                        <div class="mt-2">
                                            <label class="form-check-label text-danger">
                                                <input type="checkbox" name="delete_image" value="1" class="form-check-input">
                                                حذف الصورة الحالية
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- New Image Upload -->
                                <div class="col-12 mb-4">
                                    <label class="form-label">
                                        <i class="ti ti-photo me-1"></i>{{ $coupon->image ? 'استبدال الصورة' : 'إضافة صورة' }}
                                        <small class="text-muted">(اختياري)</small>
                                    </label>
                                    <input type="file" name="image" accept="image/*"
                                        class="dropify @error('image') is-invalid @enderror" data-height="300">
                                    @error('image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">اترك الحقل فارغاً للاحتفاظ بالصورة الحالية</small>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ti ti-device-floppy me-1"></i>تحديث الكوبون
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

            $('#couponForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let submitBtn = $(this).find('button[type="submit"]');

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span>جاري التحديث...');

                $.ajax({
                    url: '{{ route('dashboard.coupons.update', $coupon->id) }}',
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم التحديث',
                            text: 'تم تحديث الكوبون بنجاح',
                            confirmButtonText: 'موافق'
                        }).then(() => {
                            window.location.href = "{{ route('dashboard.coupons') }}";
                        });
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html(
                            '<i class="ti ti-device-floppy me-1"></i>تحديث الكوبون');

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
