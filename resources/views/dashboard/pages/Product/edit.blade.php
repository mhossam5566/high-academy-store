@extends('dashboard.layouts.layoutMaster')

@section('title', 'تعديل منتج')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/select2/select2.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">المنتجات /</span> تعديل
        </h4>
        <a href="{{ route('dashboard.product') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
        </a>
    </div>

    <form id="productForm" data-ajax data-redirect="{{ route('dashboard.product') }}"
        action="{{ route('dashboard.product.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>المعلومات الأساسية
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Multilingual Name Fields -->
                    <div class="col-12">
                        <label class="form-label fw-bold">اسم المنتج</label>
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            @foreach (config('translatable.locales') as $index => $locale)
                                <li class="nav-item">
                                    <button type="button" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                        data-bs-toggle="tab" data-bs-target="#name-{{ $locale }}">
                                        {{ strtoupper($locale) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach (config('translatable.locales') as $index => $locale)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                    id="name-{{ $locale }}">
                                    <input type="text" name="name:{{ $locale }}"
                                        value="{{ $product->translate($locale)->name }}"
                                        class="form-control @error('name:' . $locale) is-invalid @enderror"
                                        placeholder="أدخل اسم المنتج ({{ strtoupper($locale) }})">
                                    @error('name:' . $locale)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Multilingual Description Fields -->
                    <div class="col-12">
                        <label class="form-label fw-bold">الوصف</label>
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            @foreach (config('translatable.locales') as $index => $locale)
                                <li class="nav-item">
                                    <button type="button" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                        data-bs-toggle="tab" data-bs-target="#desc-{{ $locale }}">
                                        {{ strtoupper($locale) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach (config('translatable.locales') as $index => $locale)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                    id="desc-{{ $locale }}">
                                    <textarea name="description:{{ $locale }}" rows="3"
                                        class="form-control @error('description:' . $locale) is-invalid @enderror"
                                        placeholder="أدخل الوصف ({{ strtoupper($locale) }})">{{ $product->translate($locale)->description }}</textarea>
                                    @error('description:' . $locale)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الاسم المختصر</label>
                        <input type="text" name="short_name" value="{{ $product->short_name }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">تعليق (عندما يكون غير متوفر)</label>
                        <input type="text" name="commit" value="{{ $product->commit }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">المنتج متوفر؟</label>
                        <select name="state" id="state" class="form-select @error('state') is-invalid @enderror">
                            <option value="">اختر الحالة</option>
                            <option value="0" {{ $product->state == 0 ? 'selected' : '' }}>غير متوفر</option>
                            <option value="1" {{ $product->state == 1 ? 'selected' : '' }}>متوفر</option>
                            <option value="2" {{ $product->state == 2 ? 'selected' : '' }}>يمكن حجزه</option>
                            <option value="3" {{ $product->state == 3 ? 'selected' : '' }}>سيتوفر قريبا</option>
                        </select>
                        @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Stock -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-currency-dollar me-2"></i>السعر والمخزون
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">السعر</label>
                        <input type="number" name="price" id="price" value="{{ $product->price }}" step="0.01"
                            class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">الكمية</label>
                        <input type="number" name="quantity" value="{{ $product->quantity }}" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">أقصى كمية للطلب</label>
                        <input type="number" name="max_qty_for_order" value="{{ $product->max_qty_for_order }}"
                            class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">قيمة الضريبة</label>
                        <input type="number" name="tax" value="{{ $product->tax }}" step="0.01"
                            class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">ضريبة التوصيل البطيء</label>
                        <input type="number" name="slowTax" value="{{ $product->slowTax }}" step="0.01"
                            class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Offer Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-discount-2 me-2"></i>إعدادات العروض
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">لديه عرض؟</label>
                        <select name="have_offer" id="have_offer" class="form-select">
                            <option value="">اختر</option>
                            <option value="0" {{ $product->have_offer == 0 ? 'selected' : '' }}>لا</option>
                            <option value="1" {{ $product->have_offer == 1 ? 'selected' : '' }}>نعم</option>
                        </select>
                    </div>

                    <div class="col-md-4" id="offer_type_div"
                        style="display: {{ $product->have_offer == 1 ? 'block' : 'none' }};">
                        <label class="form-label">نوع العرض</label>
                        <select name="offer_type" id="offer_type" class="form-select">
                            <option value="">اختر النوع</option>
                            <option value="percentage" {{ $product->offer_type == 'percentage' ? 'selected' : '' }}>نسبة
                                مئوية</option>
                            <option value="value" {{ $product->offer_type == 'value' ? 'selected' : '' }}>قيمة ثابتة
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4" id="offer_value_div"
                        style="display: {{ $product->have_offer == 1 ? 'block' : 'none' }};">
                        <label class="form-label">قيمة العرض</label>
                        <input type="number" name="offer_value" id="offer_value" value="{{ $product->offer_value }}"
                            step="0.01" class="form-control">
                    </div>

                    <div class="col-12">
                        <div class="alert alert-info mb-0" id="final_price_alert" style="display: none;">
                            <i class="ti ti-info-circle me-2"></i>
                            <span id="final_price"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories & Classification -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-category me-2"></i>التصنيفات
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">القسم الرئيسي</label>
                        <select name="main_category_id" id="main_categories" class="form-select select2">
                            <option value="">اختر القسم</option>
                            @foreach ($main_categories as $main_category)
                                <option value="{{ $main_category->id }}"
                                    {{ $product->main_category_id == $main_category->id ? 'selected' : '' }}>
                                    {{ $main_category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">المادة الدراسية</label>
                        <select name="category_id" id="category_id" class="form-select select2">
                            <option value="">اختر المادة</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الصف الدراسي</label>
                        <select name="slider_id" id="slider_id" class="form-select select2">
                            <option value="">اختر الصف</option>
                            @foreach ($sliders as $slider)
                                <option value="{{ $slider->id }}"
                                    {{ $product->slider_id == $slider->id ? 'selected' : '' }}>
                                    {{ $slider->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">المدرس</label>
                        <select name="brand_id" id="brand_id" class="form-select select2">
                            <option value="">اختر المدرس</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الأحجام</label>
                        <select name="sizes[]" id="size" class="form-select select2" multiple>
                            @if (is_array($product->sizes))
                                @foreach ($sizes as $size)
                                    <option value="{{ $size }}"
                                        {{ in_array($size, $product->sizes) ? 'selected' : '' }}>{{ $size }}
                                    </option>
                                @endforeach
                            @else
                                @foreach ($sizes as $size)
                                    <option value="{{ $size }}"
                                        {{ in_array($size, json_decode($product->sizes ?? '[]')) ? 'selected' : '' }}>
                                        {{ $size }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">الألوان</label>
                        <select name="colors[]" id="color" class="form-select select2" multiple>
                            @if (is_array($product->colors))
                                @foreach ($colors as $color)
                                    <option value="{{ $color }}"
                                        {{ in_array($color, $product->colors) ? 'selected' : '' }}>{{ $color }}
                                    </option>
                                @endforeach
                            @else
                                @foreach ($colors as $color)
                                    <option value="{{ $color }}"
                                        {{ in_array($color, json_decode($product->colors ?? '[]')) ? 'selected' : '' }}>
                                        {{ $color }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-settings me-2"></i>الإعدادات
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">حالة المنتج</label>
                        <select name="is_deleted" class="form-select">
                            <option value="0" {{ $product->is_deleted == 0 ? 'selected' : '' }}>نشط</option>
                            <option value="1" {{ $product->is_deleted == 1 ? 'selected' : '' }}>مخفي</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">الأكثر مبيعاً</label>
                        <select name="best_seller" id="best_seller" class="form-select">
                            <option value="0" {{ $product->best_seller == 0 ? 'selected' : '' }}>لا</option>
                            <option value="1" {{ $product->best_seller == 1 ? 'selected' : '' }}>نعم</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-photo me-2"></i>الصور
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @if ($product->photo)
                        <!-- Current Main Image Preview -->
                        <div class="col-12 mb-3">
                            <label class="form-label">الصورة الرئيسية الحالية</label>
                            <div class="text-center p-3 bg-light rounded">
                                <img src="{{ $product->image_path }}" alt="Product Image"
                                    class="img-fluid rounded shadow-sm" style="max-width: 300px;">
                                <div class="mt-2">
                                    <label class="form-check-label text-danger">
                                        <input type="checkbox" name="delete_main_image" value="1"
                                            class="form-check-input">
                                        حذف الصورة الرئيسية
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-md-6">
                        <label class="form-label">
                            <i
                                class="ti ti-photo me-1"></i>{{ $product->photo ? 'استبدال الصورة الرئيسية' : 'الصورة الرئيسية' }}
                            <small class="text-muted">(اختياري)</small>
                        </label>
                        <input type="file" name="photo" accept="image/*" class="dropify" data-height="300">
                        <small class="text-muted">صورة واحدة فقط</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="ti ti-photos me-1"></i>الصور الإضافية
                            <small class="text-muted">(اختياري)</small>
                        </label>
                        <input type="file" name="images[]" accept="image/*" class="dropify" data-height="300"
                            multiple>
                        <small class="text-muted">يمكن رفع أكثر من صورة</small>
                    </div>

                    @if ($product->images && count($product->images) > 0)
                        <div class="col-12">
                            <label class="form-label">الصور الحالية (يمكنك حذف الصور غير المرغوبة)</label>
                            <div class="row g-3">
                                @foreach ($product->images as $image)
                                    <div class="col-md-3">
                                        <div class="card">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top"
                                                alt="Product Image" style="height: 200px; object-fit: cover;">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="img_{{ $image->id }}" name="delete_images[]"
                                                        value="{{ $image->id }}">
                                                    <label class="form-check-label text-danger"
                                                        for="img_{{ $image->id }}">
                                                        حذف هذه الصورة
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="card">
            <div class="card-body text-center">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="ti ti-device-floppy me-2"></i>تحديث المنتج
                </button>
            </div>
        </div>
    </form>
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

            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: 'اختر...'
            });

            // Price calculation
            function calculateFinalPrice() {
                var price = parseFloat($('#price').val()) || 0;
                var offerType = $('#offer_type').val();
                var offerValue = parseFloat($('#offer_value').val()) || 0;
                var finalPrice;

                if (offerType === "percentage") {
                    finalPrice = price - (price * offerValue / 100);
                } else if (offerType === "value") {
                    finalPrice = price - offerValue;
                } else {
                    $('#final_price_alert').hide();
                    return;
                }

                $('#final_price').text('السعر النهائي بعد الخصم: ' + finalPrice.toFixed(2) + ' جنيه');
                $('#final_price_alert').show();
            }

            $('#offer_type, #price, #offer_value').on('change input', calculateFinalPrice);

            // Calculate on page load
            calculateFinalPrice();

            // Toggle offer fields based on have_offer
            $('#have_offer').on('change', function() {
                if ($(this).val() === '1') {
                    $('#offer_type_div').show();
                    $('#offer_value_div').show();
                } else {
                    $('#offer_type_div').hide();
                    $('#offer_value_div').hide();
                    $('#offer_value').val(0);
                    $('#final_price_alert').hide();
                }
            });

            // Initialize offer fields visibility on page load
            if ($('#have_offer').val() === '1') {
                $('#offer_type_div').show();
                $('#offer_value_div').show();
            }

            // AJAX Form Submit
            $('#productForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let submitBtn = $(this).find('button[type="submit"]');

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span>جاري التحديث...');

                $.ajax({
                    url: '{{ route('dashboard.product.update') }}',
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم التحديث',
                            text: 'تم تحديث المنتج بنجاح',
                            confirmButtonText: 'موافق'
                        }).then(() => {
                            window.location.href = "{{ route('dashboard.product') }}";
                        });
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html(
                            '<i class="ti ti-device-floppy me-2"></i>تحديث المنتج');

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
