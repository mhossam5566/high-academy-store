@extends('dashboard.layouts.layoutMaster')

@section('title', 'عرض السؤال والإجابة')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">عرض السؤال والإجابة</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.faqs') }}">الأسئلة الشائعة</a></li>
                    <li class="breadcrumb-item active">عرض السؤال</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('dashboard.faqs') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
        </a>
    </div>

    <!-- FAQ Details Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-help-circle me-2"></i>تفاصيل السؤال والإجابة
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <!-- Question Section -->
                    <div class="mb-4">
                        <h3 class="text-primary mb-3">
                            <i class="ti ti-help-circle me-2"></i>السؤال
                        </h3>
                        <div class="bg-light p-3 rounded border-start border-primary border-4">
                            <p class="mb-0 fs-5">{{ $faq->question }}</p>
                        </div>
                    </div>

                    <!-- Answer Section -->
                    <div class="mb-4">
                        <h3 class="text-success mb-3">
                            <i class="ti ti-check-circle me-2"></i>الإجابة
                        </h3>
                        <div class="bg-light p-3 rounded border-start border-success border-4">
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $faq->answer }}</p>
                        </div>
                    </div>

                    <!-- FAQ Meta Information -->
                    <div class="card bg-light-subtle">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">
                                            <i class="ti ti-sort-ascending me-1"></i>الترتيب
                                        </h6>
                                        <p class="mb-0">
                                            <span class="badge bg-info fs-6">{{ $faq->display_order }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">
                                            <i class="ti ti-toggle-left me-1"></i>الحالة
                                        </h6>
                                        <p class="mb-0">
                                            {!! $faq->status_badge !!}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">
                                            <i class="ti ti-calendar-plus me-1"></i>تاريخ الإنشاء
                                        </h6>
                                        <p class="mb-0">{{ $faq->created_at->format('Y/m/d H:i') }}</p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">
                                            <i class="ti ti-calendar-edit me-1"></i>آخر تحديث
                                        </h6>
                                        <p class="mb-0">{{ $faq->updated_at->format('Y/m/d H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('dashboard.faqs') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
                        </a>
                        <div>
                            <a href="{{ route('dashboard.faqs.edit', $faq) }}" class="btn btn-primary me-2">
                                <i class="ti ti-edit me-1"></i>تعديل
                            </a>
                            <button class="btn btn-danger" onclick="deleteFaq({{ $faq->id }})">
                                <i class="ti ti-trash me-1"></i>حذف
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
    <script>
        function deleteFaq(id) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'لن تتمكن من استرجاع هذا السؤال والإجابة بعد الحذف!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('dashboard.faqs.destroy', $faq) }}',
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'تم الحذف!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    window.location.href = '{{ route('dashboard.faqs') }}';
                                });
                            } else {
                                Swal.fire(
                                    'خطأ!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'خطأ!',
                                'حدث خطأ أثناء حذف السؤال والإجابة',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
@endsection
