@extends('dashboard.layouts.layoutMaster')

@section('title', 'إضافة كلمة بحث جديدة')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">إضافة كلمة بحث جديدة</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.search-keywords') }}">كلمات البحث</a></li>
                    <li class="breadcrumb-item active">إضافة كلمة بحث جديدة</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('dashboard.search-keywords') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
        </a>
    </div>

    <!-- Form Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-search me-2"></i>بيانات كلمة البحث
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.search-keywords.store') }}" method="POST" id="keywordForm">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="keyword" class="form-label">كلمة البحث <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('keyword') is-invalid @enderror" id="keyword"
                                name="keyword" value="{{ old('keyword') }}" placeholder="أدخل كلمة البحث" required>
                            @error('keyword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="display_order" class="form-label">الترتيب</label>
                            <input type="number" class="form-control @error('display_order') is-invalid @enderror"
                                id="display_order" name="display_order" value="{{ old('display_order') }}"
                                placeholder="تلقائي" min="0">
                            @error('display_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">اتركه فارغ للترتيب التلقائي</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                required>
                                <option value="">اختر الحالة</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('dashboard.search-keywords') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i>حفظ كلمة البحث
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
        document.getElementById('keywordForm').addEventListener('submit', function(e) {
            const keyword = document.getElementById('keyword').value.trim();
            if (keyword === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يجب إدخال كلمة البحث'
                });
                return false;
            }
            return true;
        });
    </script>
@endsection
