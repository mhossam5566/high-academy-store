@extends('dashboard.layouts.layoutMaster')

@section('title', 'تعديل العرض')

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
                <span class="text-muted fw-light">العروض /</span> تعديل العرض #{{ $offer->id }}
            </h4>
            <a href="{{ route('dashboard.offers') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
            </a>
        </div>

        <!-- Edit Offer Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-discount-2 me-2"></i>تعديل معلومات العرض
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="offerForm" method="POST" action="{{ route('dashboard.offers.update', $offer->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Current Image Preview -->
                            <div class="mb-4">
                                <label class="form-label">الصورة الحالية</label>
                                <div class="text-center p-3 bg-light rounded">
                                    <img src="{{ asset('storage/images/offers/' . $offer->image) }}" alt="Offer Image"
                                        class="img-fluid rounded shadow-sm" style="max-width: 300px;">
                                </div>
                            </div>

                            <!-- New Image Upload -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="ti ti-photo me-1"></i>استبدال الصورة
                                    <small class="text-muted">(اختياري)</small>
                                </label>
                                <input type="file" name="image" accept="image/*"
                                    class="dropify @error('image') is-invalid @enderror" data-height="300">
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">اترك الحقل فارغاً للاحتفاظ بالصورة الحالية</small>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ti ti-device-floppy me-1"></i>تحديث العرض
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
                    fileSize: 'حجم الملف كبير جداً (الحد الأقصى {{ 2 }} MB).'
                }
            });

            $('#offerForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let submitBtn = $(this).find('button[type="submit"]');

                // Disable submit button
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span>جاري التحديث...');

                $.ajax({
                    url: '{{ route('dashboard.offers.update', $offer->id) }}',
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم التحديث',
                            text: 'تم تحديث العرض بنجاح',
                            confirmButtonText: 'موافق'
                        }).then(() => {
                            window.location.href = "{{ route('dashboard.offers') }}";
                        });
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html(
                            '<i class="ti ti-device-floppy me-1"></i>تحديث العرض');

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
