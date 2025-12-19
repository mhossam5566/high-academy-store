@extends('dashboard.layouts.layoutMaster')

@section('title', 'تعديل قسم رئيسي')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('admin/assets/cssbundle/dropify.min.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('admin/assets/js/bundle/dropify.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">الأقسام الرئيسية /</span> تعديل
        </h4>
        <a href="{{ route('dashboard.main_categories') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
        </a>
    </div>

    <form id="mainCategoryForm" data-ajax data-redirect="{{ route('dashboard.main_categories') }}"
        action="{{ url('dashboard/main_categories/update') }}/{{ $category->id }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="category_id" value="{{ $category->id }}">

        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>معلومات القسم الرئيسي
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">اسم القسم الرئيسي</label>
                        <input type="text" name="name" value="{{ $category->name }}"
                            class="form-control @error('name') is-invalid @enderror" placeholder="أدخل اسم القسم الرئيسي"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Upload -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-photo me-2"></i>صورة القسم
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">صورة القسم الرئيسي</label>
                        @php
                            $defaultImage = $category->icon_image ? asset('storage/' . $category->icon_image) : '';
                        @endphp
                        <input type="file" name="icon_image"
                            class="dropify form-control @error('icon_image') is-invalid @enderror"
                            data-default-file="{{ $defaultImage }}" data-max-file-size="2M"
                            data-allowed-file-extensions="jpg jpeg png gif svg">
                        @error('icon_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if ($category->icon_image)
                    <div class="mt-3">
                        <label class="form-label">الصورة الحالية:</label>
                        <div>
                            <img src="{{ asset('storage/' . $category->icon_image) }}" alt="Current Icon"
                                class="img-thumbnail" style="max-width: 150px;">
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Submit Button -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('dashboard.main_categories') }}" class="btn btn-label-secondary">
                        <i class="ti ti-x me-1"></i>إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check me-1"></i>تحديث
                    </button>
                </div>
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
                    'default': 'اسحب وأفلت الملف هنا أو انقر',
                    'replace': 'اسحب وأفلت أو انقر للاستبدال',
                    'remove': 'إزالة',
                    'error': 'عذراً، حدث خطأ ما'
                },
                error: {
                    'fileSize': 'حجم الملف كبير جداً',
                    'minWidth': 'العرض صغير جداً',
                    'maxWidth': 'العرض كبير جداً',
                    'minHeight': 'الارتفاع صغير جداً',
                    'maxHeight': 'الارتفاع كبير جداً',
                    'imageFormat': 'صيغة الصورة غير مسموح بها'
                }
            });
        });
    </script>
@endsection
