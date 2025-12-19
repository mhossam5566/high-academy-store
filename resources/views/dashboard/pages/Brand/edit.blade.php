@extends('dashboard.layouts.layoutMaster')

@section('title', 'تعديل مدرس')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('admin/assets/cssbundle/dropify.min.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('admin/assets/js/bundle/dropify.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">المدرسين /</span> تعديل
        </h4>
        <a href="{{ route('dashboard.teachers') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
        </a>
    </div>

    <form id="brandForm" data-ajax data-redirect="{{ route('dashboard.teachers') }}"
        action="{{ route('dashboard.teachers.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="brand_id" value="{{ $brand->id }}">

        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>معلومات المدرس
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Multilingual Title Fields -->
                    <div class="col-12">
                        <label class="form-label fw-bold">اسم المدرس</label>
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            @foreach (config('translatable.locales') as $index => $locale)
                                <li class="nav-item">
                                    <button type="button" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                        data-bs-toggle="tab" data-bs-target="#title-{{ $locale }}">
                                        {{ strtoupper($locale) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach (config('translatable.locales') as $index => $locale)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                    id="title-{{ $locale }}">
                                    <input type="text" name="title:{{ $locale }}"
                                        value="{{ $brand->translate($locale)->title }}"
                                        class="form-control @error('title:' . $locale) is-invalid @enderror"
                                        placeholder="أدخل اسم المدرس ({{ strtoupper($locale) }})">
                                    @error('title:' . $locale)
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
                                        placeholder="أدخل الوصف ({{ strtoupper($locale) }})">{{ $brand->translate($locale)->description }}</textarea>
                                    @error('description:' . $locale)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Upload -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-photo me-2"></i>صورة المدرس
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">الصورة الشخصية</label>
                        <input type="file" name="photo"
                            class="dropify form-control @error('photo') is-invalid @enderror"
                            data-default-file="{{ $brand->image_path }}" data-max-file-size="2M"
                            data-allowed-file-extensions="jpg jpeg png gif">
                        @error('photo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if ($brand->image_path)
                    <div class="mt-3">
                        <label class="form-label">الصورة الحالية:</label>
                        <div>
                            <img src="{{ $brand->image_path }}" alt="Current Photo" class="img-thumbnail"
                                style="max-width: 150px;">
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Submit Button -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('dashboard.teachers') }}" class="btn btn-label-secondary">
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
