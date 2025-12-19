@extends('dashboard.layouts.layoutMaster')

@section('title', 'إضافة سؤال جديد')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">إضافة سؤال جديد</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.faqs') }}">الأسئلة الشائعة</a></li>
                    <li class="breadcrumb-item active">إضافة سؤال جديد</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('dashboard.faqs') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
        </a>
    </div>

    <!-- FAQ Form Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-help-circle me-2"></i>بيانات السؤال والإجابة
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.faqs.store') }}" method="POST" id="faqForm">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="question" class="form-label">السؤال <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('question') is-invalid @enderror"
                                id="question" name="question" value="{{ old('question') }}" placeholder="أدخل السؤال هنا"
                                required>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="display_order" class="form-label">الترتيب</label>
                            <input type="number" class="form-control @error('display_order') is-invalid @enderror"
                                id="display_order" name="display_order" value="{{ old('display_order') }}"
                                placeholder="اتركه فارغ للترتيب التلقائي" min="0">
                            @error('display_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">سيتم ترتيب الأسئلة حسب هذا الرقم (الأصغر أولاً)</div>
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

                <div class="mb-3">
                    <label for="answer" class="form-label">الإجابة <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('answer') is-invalid @enderror" id="answer" name="answer" rows="5"
                        placeholder="أدخل الإجابة هنا" required>{{ old('answer') }}</textarea>
                    @error('answer')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('dashboard.faqs') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i>حفظ السؤال والإجابة
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
        // Auto-resize textarea
        document.getElementById('answer').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Form validation
        document.getElementById('faqForm').addEventListener('submit', function(e) {
            const question = document.getElementById('question').value.trim();
            const answer = document.getElementById('answer').value.trim();

            if (question === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يجب إدخال السؤال'
                });
                return false;
            }

            if (answer === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يجب إدخال الإجابة'
                });
                return false;
            }

            return true;
        });
    </script>
@endsection
