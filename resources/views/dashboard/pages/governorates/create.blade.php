@extends('dashboard.layouts.layoutMaster')

@section('title', 'إضافة محافظة جديدة')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">إضافة محافظة جديدة</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.governorates.index') }}">المحافظات</a></li>
                    <li class="breadcrumb-item active">إضافة محافظة جديدة</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('dashboard.governorates.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
        </a>
    </div>

    <!-- Governorate Form Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-map-pin me-2"></i>بيانات المحافظة
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.governorates.store') }}" method="POST" id="governorateForm">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="governorate_name_ar" class="form-label">الاسم باللغة العربية <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('governorate_name_ar') is-invalid @enderror"
                                id="governorate_name_ar" name="governorate_name_ar" value="{{ old('governorate_name_ar') }}"
                                placeholder="أدخل اسم المحافظة بالعربية" required>
                            @error('governorate_name_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="governorate_name_en" class="form-label">الاسم باللغة الإنجليزية <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('governorate_name_en') is-invalid @enderror"
                                id="governorate_name_en" name="governorate_name_en" value="{{ old('governorate_name_en') }}"
                                placeholder="Enter governorate name in English" required>
                            @error('governorate_name_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="home_shipping_price" class="form-label">
                                <i class="ti ti-home me-1"></i>سعر التوصيل للمنزل (جنيه) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control @error('home_shipping_price') is-invalid @enderror"
                                id="home_shipping_price" name="home_shipping_price"
                                value="{{ old('home_shipping_price', 0) }}" placeholder="0.00" step="0.01" min="0"
                                required>
                            @error('home_shipping_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="post_shipping_price" class="form-label">
                                <i class="ti ti-mailbox me-1"></i>سعر التوصيل لمكتب البريد (جنيه) <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control @error('post_shipping_price') is-invalid @enderror"
                                id="post_shipping_price" name="post_shipping_price"
                                value="{{ old('post_shipping_price', 0) }}" placeholder="0.00" step="0.01"
                                min="0" required>
                            @error('post_shipping_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('dashboard.governorates.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i>حفظ المحافظة
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
    <script>
        // Form validation
        document.getElementById('governorateForm').addEventListener('submit', function(e) {
            const nameAr = document.getElementById('governorate_name_ar').value.trim();
            const nameEn = document.getElementById('governorate_name_en').value.trim();
            const homePrice = document.getElementById('home_shipping_price').value;
            const postPrice = document.getElementById('post_shipping_price').value;

            if (nameAr === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يجب إدخال اسم المحافظة بالعربية'
                });
                return false;
            }

            if (nameEn === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يجب إدخال اسم المحافظة بالإنجليزية'
                });
                return false;
            }

            if (homePrice === '' || parseFloat(homePrice) < 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يجب إدخال سعر التوصيل للمنزل بشكل صحيح'
                });
                return false;
            }

            if (postPrice === '' || parseFloat(postPrice) < 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يجب إدخال سعر التوصيل لمكتب البريد بشكل صحيح'
                });
                return false;
            }

            return true;
        });
    </script>
@endsection
