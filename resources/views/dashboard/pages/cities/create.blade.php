@extends('dashboard.layouts.layoutMaster')

@section('title', 'إضافة مدينة جديدة')

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
                <span class="text-muted fw-light">المدن /</span> إضافة مدينة جديدة
            </h4>
            <a href="{{ route('dashboard.cities.index', $selectedGovernorate ? ['governorate' => $selectedGovernorate->id] : []) }}"
                class="btn btn-secondary">
                <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
            </a>
        </div>

        <!-- Create City Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-map-pin me-2"></i>معلومات المدينة
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.cities.store') }}" method="POST" id="cityForm">
                            @csrf

                            <!-- Governorate -->
                            <div class="mb-4">
                                <label for="governorate_id" class="form-label">
                                    <i class="ti ti-map me-1"></i>المحافظة
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('governorate_id') is-invalid @enderror"
                                    id="governorate_id" name="governorate_id" required>
                                    <option value="">اختر المحافظة</option>
                                    @foreach ($governorates as $governorate)
                                        <option value="{{ $governorate->id }}"
                                            {{ (int) old('governorate_id', optional($selectedGovernorate)->id) === (int) $governorate->id ? 'selected' : '' }}>
                                            {{ $governorate->name_ar }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('governorate_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <!-- Arabic Name -->
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="name_ar" class="form-label">
                                            <i class="ti ti-language me-1"></i>الاسم باللغة العربية
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror"
                                            id="name_ar" name="name_ar" value="{{ old('name_ar') }}"
                                            placeholder="مثال: القاهرة" required>
                                        @error('name_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- English Name -->
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="name_en" class="form-label">
                                            <i class="ti ti-language me-1"></i>الاسم باللغة الإنجليزية
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                                            id="name_en" name="name_en" value="{{ old('name_en') }}"
                                            placeholder="Example: Cairo" required>
                                        @error('name_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" name="status"
                                        value="1" @checked(old('status', true))>
                                    <label class="form-check-label" for="status">
                                        <i class="ti ti-check me-1"></i>المدينة نشطة
                                    </label>
                                </div>
                                <small class="text-muted">تفعيل المدينة يسمح للعملاء باختيارها عند الطلب</small>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ti ti-device-floppy me-1"></i>حفظ المدينة
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
        const governorateSelect = document.getElementById('governorate_id');
        const defaultGovernorate = @json(old('governorate_id', optional($selectedGovernorate)->id));

        if (defaultGovernorate) {
            governorateSelect.value = defaultGovernorate.toString();
        }

        document.getElementById('cityForm').addEventListener('submit', function(event) {
            const governorate = governorateSelect.value.trim();
            const nameAr = document.getElementById('name_ar').value.trim();
            const nameEn = document.getElementById('name_en').value.trim();

            if (!governorate) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يرجى اختيار المحافظة',
                    confirmButtonText: 'موافق'
                });
                return;
            }

            if (!nameAr) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يرجى إدخال اسم المدينة بالعربية',
                    confirmButtonText: 'موافق'
                });
                return;
            }

            if (!nameEn) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يرجى إدخال اسم المدينة بالإنجليزية',
                    confirmButtonText: 'موافق'
                });
            }
        });
    </script>
@endsection
